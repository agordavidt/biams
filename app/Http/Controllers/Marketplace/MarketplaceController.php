<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\LGA;
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
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


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

    // Load relationships
    $listing->load(['category', 'user.farmerProfile', 'images']);
    
    // Extract images collection
    $images = $listing->images;
    
    // Extract seller
    $seller = $listing->user;
    
    // Get related listings
    $relatedListings = MarketplaceListing::active()
        ->where('category_id', $listing->category_id)
        ->where('id', '!=', $listing->id)
        ->limit(6)
        ->get();

    return view('marketplace.show', compact('listing', 'images', 'seller', 'relatedListings'));
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
        'inquiry_message' => 'required|string|max:1000',
    ]);

    DB::beginTransaction();
    try {
        // Create inquiry record
        $inquiry = MarketplaceInquiry::create([
            'listing_id' => $listing->id,
            'buyer_name' => $validated['buyer_name'],
            'buyer_phone' => $validated['buyer_phone'],
            'buyer_email' => $validated['buyer_email'] ?? null,
            'message' => $validated['inquiry_message'],
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
         $lgas = LGA::orderBy('name')->get(['id', 'name']);
        
       return view('farmer.marketplace.create', compact('categories', 'lgas'));
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
     * Initiate marketplace subscription payment with Credo
     */
    public function initiatePayment(Request $request)
    {
        $user = Auth::user();

        // Check if already subscribed using the model method
        $activeSubscription = MarketplaceSubscription::where('user_id', $user->id)
            ->active()
            ->first();

        if ($activeSubscription) {
            return back()->with('info', 'You already have an active marketplace subscription until ' . 
                $activeSubscription->end_date->format('d M, Y'));
        }

        try {
            $credoPublicKey = config('services.credo.public_key');
            $credoBaseUrl = config('services.credo.base_url');
            
            // Validate Credo configuration
            if (!$credoPublicKey || !$credoBaseUrl) {
                Log::error('Credo configuration missing');
                return back()->with('error', 'Payment gateway configuration error. Please contact support.');
            }

            $reference = 'MARKET-' . $user->id . '-' . time();
            
            $requestData = [
                'amount' => (int) round(self::ANNUAL_FEE * 100), // Convert to kobo
                'email' => $user->email,
                'customerFirstName' => $user->first_name ?? 'Farmer',
                'customerLastName' => $user->last_name ?? 'User',
                'customerPhoneNumber' => $user->phone ?? '2348000000000',
                'currency' => 'NGN',
                'channels' => ['card', 'bank'],
                'reference' => $reference,
                'bearer' => 0,
                'callbackUrl' => route('farmer.marketplace.payment.callback'),
                'metadata' => [
                    'subscription_type' => 'marketplace_annual',
                    'user_id' => $user->id,
                    'amount' => self::ANNUAL_FEE,
                    'duration_days' => 365,
                ]
            ];
            
            Log::info('Marketplace Credo API Request:', $requestData);
            
            $response = Http::asJson()->withHeaders([
                'Authorization' => $credoPublicKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($credoBaseUrl . '/transaction/initialize', $requestData);
            
            Log::info('Marketplace Credo API Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Marketplace Credo API Success Response:', $data);
                
                if (isset($data['data']['authorizationUrl'])) {
                    // Create pending subscription record
                    DB::beginTransaction();
                    try {
                        $subscriptionData = [
                            'user_id' => $user->id,
                            'transaction_reference' => $reference,
                            'amount' => self::ANNUAL_FEE,
                            'status' => 'pending',
                            'payment_method' => 'credo',
                            'payment_details' => $data
                        ];

                        Log::info('Creating subscription with data:', $subscriptionData);
                        
                        $subscription = MarketplaceSubscription::create($subscriptionData);

                        DB::commit();
                        
                        Log::info('Subscription created successfully:', ['subscription_id' => $subscription->id]);
                        
                        // Store reference in session for callback handling
                        session(['current_marketplace_payment_reference' => $reference]);
                        
                        // Redirect to Credo payment page
                        return redirect($data['data']['authorizationUrl']);
                        
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Marketplace subscription creation error: ' . $e->getMessage());
                        Log::error('Error trace: ' . $e->getTraceAsString());
                        
                        // Check for specific database errors
                        if (str_contains($e->getMessage(), 'SQLSTATE')) {
                            Log::error('Database error details:', [
                                'error_code' => $e->getCode(),
                                'user_id' => $user->id
                            ]);
                        }
                        
                        return back()->with('error', 'Failed to create subscription record. Please try again.');
                    }
                } else {
                    Log::error('Credo response missing authorization URL:', $data);
                    return back()->with('error', 'Payment gateway response error. Please try again.');
                }
                
            } else {
                $errorBody = $response->body();
                Log::error('Marketplace Credo initialization failed:', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $errorBody
                ]);
                
                return back()->with('error', 'Payment gateway error. Please try again later.');
            }
            
        } catch (\Exception $e) {
            Log::error('Marketplace Credo API error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Unable to process payment at this time. Please try again later.');
        }
    }



    /**
     * Handle marketplace subscription payment callback from Credo
     */
    public function handlePaymentCallback(Request $request)
    {
        // Log incoming callback query for diagnostics
        Log::info('Marketplace Credo callback received', [
            'query' => $request->query(),
        ]);
        
        $reference = $request->query('reference')
            ?? $request->query('ref')
            ?? $request->query('payment_reference');
        
        $transRef = $request->query('transRef')
            ?? $request->query('transactionReference')
            ?? $request->query('credoReference');
        
        if (!$transRef && $reference) {
            // Try to get transaction reference from our stored data
            $transRef = session('current_marketplace_payment_reference');
        }

        if (!$transRef) {
            return redirect()->route('farmer.marketplace.my-listings')
                ->with('error', 'Invalid payment reference.');
        }

        try {
            $verificationResponse = $this->verifyCredoPayment($transRef);

            if ($verificationResponse['success']) {
                $data = $verificationResponse['data'];
                $paymentData = data_get($data, 'data') ?? $data;
                
                // Extract metadata from verification response
                $metadataArray = data_get($data, 'data.data.metadata') 
                    ?? data_get($data, 'data.metadata') 
                    ?? data_get($data, 'metadata');
                
                $userId = null;
                
                // Handle different metadata formats
                if (is_array($metadataArray)) {
                    foreach ($metadataArray as $item) {
                        if (is_array($item) && data_get($item, 'insightTag') === 'user_id') {
                            $userId = data_get($item, 'insightTagValue');
                        }
                    }
                    // Handle associative metadata
                    if (!$userId) $userId = data_get($metadataArray, 'user_id');
                }

                if (!$userId) {
                    return redirect()->route('farmer.marketplace.my-listings')
                        ->with('error', 'Invalid payment data.');
                }

                $user = Auth::user();
                
                // Verify user matches
                if ($user->id != $userId) {
                    Log::error('Marketplace payment user mismatch', [
                        'payment_user_id' => $userId,
                        'current_user_id' => $user->id
                    ]);
                    return redirect()->route('farmer.marketplace.my-listings')
                        ->with('error', 'Payment verification failed.');
                }

                // Find the subscription record
                $subscription = MarketplaceSubscription::where('transaction_reference', $transRef)
                    ->where('user_id', $user->id)
                    ->first();

                if (!$subscription) {
                    // Try to find by reference pattern
                    $subscription = MarketplaceSubscription::where('transaction_reference', 'like', 'MARKET-' . $user->id . '%')
                        ->where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->first();
                }

                if (!$subscription) {
                    return redirect()->route('farmer.marketplace.my-listings')
                        ->with('error', 'Subscription record not found.');
                }

                DB::beginTransaction();
                try {
                    // Update subscription with payment details
                    $subscription->update([
                        'status' => 'paid',
                        'payment_method' => 'credo',
                        'payment_details' => array_merge(
                            $subscription->payment_details ?? [],
                            $paymentData,
                            ['verification_response' => $data]
                        ),
                        'paid_at' => now(),
                        'start_date' => now(),
                        'end_date' => now()->addDays(365), // 1 year subscription
                    ]);

                    // Clear session reference
                    session()->forget('current_marketplace_payment_reference');

                    DB::commit();

                    return redirect()->route('farmer.marketplace.my-listings')
                        ->with('success', 'Payment successful! Your marketplace subscription is now active until ' . 
                            $subscription->end_date->format('d M, Y'));
                        
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Marketplace subscription update error: ' . $e->getMessage());
                    return redirect()->route('farmer.marketplace.my-listings')
                        ->with('error', 'Failed to activate subscription. Please contact support.');
                }
                    
            } else {
                Log::error('Marketplace payment verification failed', [
                    'transRef' => $transRef,
                    'reference' => $reference,
                ]);
                
                // Update subscription as failed
                if ($transRef) {
                    $subscription = MarketplaceSubscription::where('transaction_reference', $transRef)->first();
                    if ($subscription) {
                        $subscription->update([
                            'status' => 'failed',
                            'payment_details' => array_merge(
                                $subscription->payment_details ?? [],
                                ['verification_failed' => true, 'error' => $verificationResponse['message']]
                            )
                        ]);
                    }
                }
                
                return redirect()->route('farmer.marketplace.my-listings')
                    ->with('error', 'Payment verification failed. Please try again or contact support.');
            }
            
        } catch (\Exception $e) {
            Log::error('Marketplace payment callback error: ' . $e->getMessage(), [
                'reference' => $reference,
                'transRef' => $transRef,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('farmer.marketplace.my-listings')
                ->with('error', 'Error processing payment. Please contact support with reference: ' . ($transRef ?? $reference));
        }
    }


    /**
 * Verify payment with Credo API (Same pattern as resource payment)
 */
private function verifyCredoPayment($transRef)
{
    try {
        $credoSecretKey = config('services.credo.secret_key');
        $credoBaseUrl = config('services.credo.base_url');
        
        $response = Http::withHeaders([
            'Authorization' => $credoSecretKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get($credoBaseUrl . '/transaction/' . $transRef . '/verify');
        
        Log::info('Marketplace Credo Verify Response Status: ' . $response->status());
        
        if ($response->successful()) {
            $data = $response->json();
            Log::info('Marketplace Credo Verify Response Body:', $data);
            
            $statusRaw = data_get($data, 'data.status');
            $statusLower = is_string($statusRaw) ? strtolower($statusRaw) : null;
            $statusIsZero = ((string) $statusRaw) === '0';
            $statusMessageRaw = (string) (data_get($data, 'data.statusMessage') ?? data_get($data, 'data.message') ?? '');
            $statusMessage = strtolower($statusMessageRaw);
            $statusCode = data_get($data, 'data.statusCode')
                ?? data_get($data, 'data.code')
                ?? data_get($data, 'data.responseCode');
            
            $approvedByMessage = str_contains($statusMessage, 'success')
                || str_contains($statusMessage, 'approved');
            
            $isApproved = $statusIsZero
                || in_array($statusLower, ['success', 'successful', 'approved', 'completed', 'paid'], true)
                || in_array((string) $statusCode, ['0', '00'], true)
                || $approvedByMessage;
            
            if ($isApproved) {
                return [
                    'success' => true,
                    'transaction_id' => data_get($data, 'data.id') ?? data_get($data, 'data.transRef'),
                    'data' => $data
                ];
            }
        }
        
        Log::error('Marketplace Credo Verify failed', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        return [
            'success' => false,
            'message' => 'Payment verification failed'
        ];
        
    } catch (\Exception $e) {
        Log::error('Marketplace Credo verification error: ' . $e->getMessage());
        
        return [
            'success' => false,
            'message' => 'Verification service unavailable'
        ];
    }
}
    
   
    /**
     * Handle multiple image uploads without thumbnail generation.
     */
    private function handleImageUploads(array $images, MarketplaceListing $listing)
    {
        $sortOrder = $listing->images()->count();

        foreach ($images as $index => $imageFile) {
            // Store the original image directly using Laravel's Storage facade
            $path = $imageFile->store('marketplace/' . $listing->id, 'public');
            
            // Thumbnail generation logic removed:
            $thumbnailPath = null; 

            MarketplaceListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
                // Set thumbnail_path to null since we are not generating one
                'thumbnail_path' => $thumbnailPath, 
                'sort_order' => $sortOrder++,
                'is_primary' => $index === 0 && $listing->images()->count() === 0,
            ]);
        }
    }

    // REMOVED: private function generateThumbnail($imageFile, $thumbnailPath)
    // The Intervention Image-dependent method is completely removed.
    // The calling line in handleImageUploads has been updated.

    /**
     * Remove images from listing.
     */
    private function removeImages(array $imageIds, MarketplaceListing $listing)
    {
        $images = MarketplaceListingImage::whereIn('id', $imageIds)
            ->where('listing_id', $listing->id)
            ->get();

        foreach ($images as $image) {
            // Delete original image
            Storage::disk('public')->delete($image->image_path); 
            // Attempt to delete thumbnail if path exists
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