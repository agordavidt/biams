<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Market\MarketplaceCategory;
use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceListingImage;
use App\Models\Market\MarketplaceSubscription;
use App\Models\Market\MarketplaceInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class MarketplaceController extends Controller
{
    private const ANNUAL_FEE = 5000.00; // NGN 5,000
    private const MAX_IMAGES = 5;
    private const MAX_IMAGE_SIZE = 2048; // 2MB in KB

    /**
     * Display the public marketplace homepage.
     */
    public function index(Request $request)
    {
        $categories = MarketplaceCategory::active()->withCount('activeListings')->get();

        $query = MarketplaceListing::with(['category', 'user', 'images'])
            ->active();

        // Apply filters
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('location')) {
            $query->byLocation($request->location);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $listings = $query->paginate(12)->withQueryString();

        return view('marketplace.index', compact('listings', 'categories'));
    }

    /**
     * Display a single listing with details.
     */
    public function show(MarketplaceListing $listing)
    {
        // Check if listing is accessible
        if (!$listing->is_active && (!Auth::check() || Auth::id() !== $listing->user_id)) {
            abort(404, 'This listing is not available.');
        }

        // Increment view count
        $listing->incrementViewCount();

        $listing->load(['category', 'user.farmerProfile', 'images']);
        $relatedListings = MarketplaceListing::active()
            ->where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('listing', 'relatedListings'));
    }

    /**
     * Handle contact farmer inquiry (Lead Generation).
     */
    public function contactFarmer(Request $request, MarketplaceListing $listing)
    {
        $validated = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
            'buyer_email' => 'nullable|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Create inquiry record
            $inquiry = MarketplaceInquiry::create([
                'listing_id' => $listing->id,
                'buyer_name' => $validated['buyer_name'],
                'buyer_phone' => $validated['buyer_phone'],
                'buyer_email' => $validated['buyer_email'] ?? null,
                'message' => $validated['message'],
                'buyer_ip' => $request->ip(),
                'status' => 'new',
            ]);

            // Increment inquiry count
            $listing->incrementInquiryCount();

            // Send notification to farmer (email/SMS)
            try {
                // Mail::to($listing->user->email)->send(new NewMarketplaceInquiry($listing, $inquiry));
                // TODO: Implement SMS notification via your SMS gateway
            } catch (\Exception $e) {
                Log::error('Failed to send inquiry notification: ' . $e->getMessage());
            }

            DB::commit();

            return back()->with('success', 'Your inquiry has been sent to the farmer. They will contact you shortly.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Marketplace inquiry error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send inquiry. Please try again.');
        }
    }

    /**
     * Display farmer's listings dashboard.
     */
    public function myListings()
    {
        $user = Auth::user();
        
        // Check if user has farmer role
        if (!$user->hasRole('User')) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        $subscription = MarketplaceSubscription::where('user_id', $user->id)
            ->where('status', 'paid')
            ->latest('end_date')
            ->first();

        $isSubscribed = $subscription && $subscription->is_active;
        $subscriptionEndDate = $subscription?->end_date;
        $daysRemaining = $subscription?->days_remaining ?? 0;

        $listings = MarketplaceListing::where('user_id', $user->id)
            ->withCount('inquiries')
            ->latest()
            ->paginate(10);

        // Statistics
        $stats = [
            'total' => $listings->total(),
            'active' => MarketplaceListing::where('user_id', $user->id)->where('status', 'active')->count(),
            'pending' => MarketplaceListing::where('user_id', $user->id)->where('status', 'pending_review')->count(),
            'expired' => MarketplaceListing::where('user_id', $user->id)->where('status', 'expired')->count(),
            'total_views' => MarketplaceListing::where('user_id', $user->id)->sum('view_count'),
            'total_inquiries' => MarketplaceListing::where('user_id', $user->id)->sum('inquiry_count'),
        ];

        return view('farmer.marketplace.my-listings', compact(
            'listings',
            'isSubscribed',
            'subscriptionEndDate',
            'daysRemaining',
            'stats'
        ));
    }

    /**
     * Show the form to create a new listing.
     */
    public function create()
    {
        $this->authorize('create', MarketplaceListing::class);

        $categories = MarketplaceCategory::active()->get();
        
        return view('farmer.marketplace.create', compact('categories'));
    }

    /**
     * Store a new listing.
     */
    public function store(Request $request)
    {
        $this->authorize('create', MarketplaceListing::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0|max:999999999.99',
            'unit' => 'required|string|max:50',
            'quantity' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:marketplace_categories,id',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'expires_in' => 'required|integer|in:7,14,30,60,90',
            'images' => 'required|array|min:1|max:' . self::MAX_IMAGES,
            'images.*' => 'image|mimes:jpeg,jpg,png|max:' . self::MAX_IMAGE_SIZE,
        ]);

        DB::beginTransaction();
        try {
            // Create listing
            $listing = MarketplaceListing::create([
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'unit' => $validated['unit'],
                'quantity' => $validated['quantity'],
                'location' => $validated['location'],
                'contact' => $validated['contact'],
                'status' => 'pending_review',
                'expires_at' => Carbon::now()->addDays($validated['expires_in']),
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($request->file('images'), $listing);
            }

            DB::commit();

            return redirect()->route('farmer.marketplace.my-listings')
                ->with('success', 'Listing submitted for review. You will be notified once approved.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing creation error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create listing. Please try again.');
        }
    }

    /**
     * Show the form to edit a listing.
     */
    public function edit(MarketplaceListing $listing)
    {
        $this->authorize('update', $listing);

        $categories = MarketplaceCategory::active()->get();
        $listing->load('images');

        return view('farmer.marketplace.edit', compact('listing', 'categories'));
    }

    /**
     * Update a listing.
     */
    public function update(Request $request, MarketplaceListing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0|max:999999999.99',
            'unit' => 'required|string|max:50',
            'quantity' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:marketplace_categories,id',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'expires_in' => 'sometimes|integer|in:7,14,30,60,90',
            'images' => 'sometimes|array|max:' . self::MAX_IMAGES,
            'images.*' => 'image|mimes:jpeg,jpg,png|max:' . self::MAX_IMAGE_SIZE,
            'remove_images' => 'sometimes|array',
            'remove_images.*' => 'exists:marketplace_listing_images,id',
        ]);

        DB::beginTransaction();
        try {
            // Update listing
            $listing->update([
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'unit' => $validated['unit'],
                'quantity' => $validated['quantity'],
                'location' => $validated['location'],
                'contact' => $validated['contact'],
            ]);

            // Update expiry if provided
            if ($request->filled('expires_in')) {
                $listing->update([
                    'expires_at' => Carbon::now()->addDays($validated['expires_in']),
                ]);
            }

            // If status was rejected, change to pending_review after edit
            if ($listing->status === 'rejected') {
                $listing->update([
                    'status' => 'pending_review',
                    'rejection_reason' => null,
                ]);
            }

            // Handle image removal
            if ($request->filled('remove_images')) {
                $this->removeImages($request->remove_images, $listing);
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $currentImageCount = $listing->images()->count();
                $maxNewImages = self::MAX_IMAGES - $currentImageCount;
                
                if ($maxNewImages > 0) {
                    $newImages = array_slice($request->file('images'), 0, $maxNewImages);
                    $this->handleImageUploads($newImages, $listing);
                }
            }

            DB::commit();

            return redirect()->route('farmer.marketplace.my-listings')
                ->with('success', 'Listing updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update listing. Please try again.');
        }
    }

    /**
     * Delete a listing.
     */
    public function destroy(MarketplaceListing $listing)
    {
        $this->authorize('delete', $listing);

        DB::beginTransaction();
        try {
            // Delete all images
            foreach ($listing->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                if ($image->thumbnail_path) {
                    Storage::disk('public')->delete($image->thumbnail_path);
                }
            }

            $listing->delete();

            DB::commit();

            return redirect()->route('farmer.marketplace.my-listings')
                ->with('success', 'Listing deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete listing. Please try again.');
        }
    }

    /**
     * Display inquiries received for farmer's listings.
     */
    public function myLeads()
    {
        $user = Auth::user();

        $inquiries = MarketplaceInquiry::whereHas('listing', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('listing')
        ->latest()
        ->paginate(15);

        $stats = [
            'total' => MarketplaceInquiry::whereHas('listing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'new' => MarketplaceInquiry::whereHas('listing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'new')->count(),
        ];

        return view('farmer.marketplace.leads', compact('inquiries', 'stats'));
    }

    /**
     * Initiate marketplace subscription payment.
     */
    public function initiatePayment(Request $request)
    {
        $user = Auth::user();

        // Check if already subscribed
        $activeSubscription = MarketplaceSubscription::where('user_id', $user->id)
            ->active()
            ->first();

        if ($activeSubscription) {
            return back()->with('info', 'You already have an active marketplace subscription until ' . 
                $activeSubscription->end_date->format('d M, Y'));
        }

        DB::beginTransaction();
        try {
            $reference = 'MARKET-' . $user->id . '-' . time();

            $subscription = MarketplaceSubscription::create([
                'user_id' => $user->id,
                'transaction_reference' => $reference,
                'amount' => self::ANNUAL_FEE,
                'status' => 'pending',
                'payment_method' => 'credo',
            ]);

            DB::commit();

            // TODO: Integrate with Credo Payment Gateway
            // $credoPayload = [
            //     'amount' => self::ANNUAL_FEE * 100,
            //     'email' => $user->email,
            //     'reference' => $reference,
            //     'callback_url' => route('farmer.marketplace.payment.verify'),
            // ];
            // return redirect()->away(CredoService::initiate($credoPayload));

            // Mock redirect for now
            return redirect()->route('farmer.marketplace.payment.verify', ['reference' => $reference])
                ->with('info', 'Payment initiated (Mock). Reference: ' . $reference);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment initiation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    /**
     * Verify marketplace subscription payment.
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('farmer.marketplace.my-listings')
                ->with('error', 'Invalid payment reference.');
        }

        $subscription = MarketplaceSubscription::where('transaction_reference', $reference)->first();

        if (!$subscription) {
            return redirect()->route('farmer.marketplace.my-listings')
                ->with('error', 'Subscription not found.');
        }

        // TODO: Verify with Credo Payment Gateway
        // $verificationResult = CredoService::verify($reference);

        // Mock verification (simulate success)
        $verificationResult = (object)[
            'status' => 'success',
            'amount' => self::ANNUAL_FEE,
        ];

        DB::beginTransaction();
        try {
            if ($verificationResult->status === 'success' && $verificationResult->amount == self::ANNUAL_FEE) {
                $subscription->markAsPaid();

                DB::commit();

                return redirect()->route('farmer.marketplace.my-listings')
                    ->with('success', 'Payment successful! Your marketplace subscription is now active until ' . 
                        $subscription->end_date->format('d M, Y'));
            } else {
                $subscription->markAsFailed();
                DB::commit();

                return redirect()->route('farmer.marketplace.my-listings')
                    ->with('error', 'Payment verification failed. Please try again or contact support.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment verification error: ' . $e->getMessage());
            return redirect()->route('farmer.marketplace.my-listings')
                ->with('error', 'An error occurred during payment verification.');
        }
    }

    /**
     * Handle multiple image uploads with thumbnail generation.
     */
    private function handleImageUploads(array $images, MarketplaceListing $listing)
    {
        $sortOrder = $listing->images()->count();

        foreach ($images as $index => $imageFile) {
            $path = $imageFile->store('marketplace/' . $listing->id, 'public');
            
            // Generate thumbnail
            $thumbnailPath = 'marketplace/' . $listing->id . '/thumb_' . basename($path);
            $this->generateThumbnail($imageFile, $thumbnailPath);

            MarketplaceListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
                'thumbnail_path' => $thumbnailPath,
                'sort_order' => $sortOrder++,
                'is_primary' => $index === 0 && $listing->images()->count() === 0,
            ]);
        }
    }

    /**
     * Generate thumbnail for image.
     */
    private function generateThumbnail($imageFile, $thumbnailPath)
    {
        try {
            $img = Image::make($imageFile);
            $img->fit(300, 300);
            Storage::disk('public')->put($thumbnailPath, (string) $img->encode());
        } catch (\Exception $e) {
            Log::warning('Thumbnail generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove images from listing.
     */
    private function removeImages(array $imageIds, MarketplaceListing $listing)
    {
        $images = MarketplaceListingImage::whereIn('id', $imageIds)
            ->where('listing_id', $listing->id)
            ->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
            $image->delete();
        }

        // Ensure at least one image remains as primary
        if ($listing->images()->count() > 0 && !$listing->images()->where('is_primary', true)->exists()) {
            $listing->images()->first()->makePrimary();
        }
    }
}