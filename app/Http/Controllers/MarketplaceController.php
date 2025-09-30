<?php

namespace App\Http\Controllers;

use App\Models\Market\MarketplaceCategory;
use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MarketplaceController extends Controller
{
    /**
     * Display the marketplace homepage with all active listings
     */
    public function index(Request $request)
    {
        // Get all categories for filtering
        $categories = MarketplaceCategory::where('is_active', true)->get();
        
        // Build query
        $query = MarketplaceListing::with(['category', 'user'])
                               ->where('availability', 'available')
                               ->where('expires_at', '>', now());
        
        // Apply filters if provided
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        // Get listings with pagination
        $listings = $query->latest()->paginate(12);
        
        return view('user.marketplace.index', compact('listings', 'categories'));
    }
    
    /**
     * Display the user's listings dashboard
     */
    public function myListings()
    {
        $user = Auth::user();
        $listings = $user->marketplaceListings()->latest()->paginate(10);
        
        return view('user.marketplace.my-listings', compact('listings'));
    }
    
    /**
     * Show the form for creating a new listing
     */
    public function create()
    {
        $categories = MarketplaceCategory::where('is_active', true)->get();
        return view('user.marketplace.create', compact('categories'));
    }
    
    /**
     * Store a newly created listing
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:marketplace_categories,id',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:11', 
            'image' => 'nullable|image|max:2048', // 2MB max
            'expires_in' => 'required|integer|in:7,14,30,60', // Days until expiration
        ]);
        
        $listing = new MarketplaceListing();
        $listing->user_id = Auth::id();
        $listing->title = $request->title;
        $listing->description = $request->description;
        $listing->price = $request->price;
        $listing->unit = $request->unit;
        $listing->quantity = $request->quantity;
        $listing->category_id = $request->category_id;
        $listing->location = $request->location;
        $listing->contact = $request->contact;
        $listing->expires_at = Carbon::now()->addDays($request->expires_in);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('marketplace', 'public');
            $listing->image = $path;
        }
        
        $listing->save();
        
        return redirect()->route('marketplace.my-listings')
            ->with('success', 'Your listing has been successfully created.');
    }
    
    /**
     * Display the specified listing details
     */
    public function show(MarketplaceListing $listing)
    {
        // Increment view count or implement other analytics here if needed
        
        // Check if the listing is active and not expired
        if ($listing->availability !== 'active' || $listing->expires_at < now()) {
            return redirect()->route('marketplace.index')
                ->with('error', 'This listing is no longer available.');
        }
        
        // Load seller info for display (only public info)
        $seller = $listing->user;
        
        return view('user.marketplace.show', compact('listing', 'seller'));
    }
    
    /**
     * Show the form for editing a listing
     */
    public function edit(MarketplaceListing $listing)
    {
        // Check if the authenticated user owns this listing
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('marketplace.my-listings')
                ->with('error', 'You do not have permission to edit this listing.');
        }
        
        $categories = MarketplaceCategory::where('is_active', true)->get();
        
        return view('user.marketplace.edit', compact('listing', 'categories'));
    }
    
    /**
     * Update the specified listing
     */
    public function update(Request $request, MarketplaceListing $listing)
    {
        // Check if the authenticated user owns this listing
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('marketplace.my-listings')
                ->with('error', 'You do not have permission to update this listing.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:marketplace_categories,id',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:11',         
            'image' => 'nullable|image|max:2048', // 2MB max
            'expires_in' => 'nullable|integer|in:7,14,30,60', 
        ]);
        
        $listing->title = $request->title;
        $listing->description = $request->description;
        $listing->price = $request->price;
        $listing->unit = $request->unit;
        $listing->quantity = $request->quantity;
        $listing->category_id = $request->category_id;
        $listing->location = $request->location;
        $listing->contact = $request->contact;
        
        // Extend expiration date if requested
        if ($request->has('expires_in')) {
            $listing->expires_at = Carbon::now()->addDays($request->expires_in);
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($listing->image) {
                Storage::disk('public')->delete($listing->image);
            }
            
            $path = $request->file('image')->store('marketplace', 'public');
            $listing->image = $path;
        }
        
        $listing->save();
        
        return redirect()->route('marketplace.my-listings')
            ->with('success', 'Your listing has been successfully updated.');
    }
    
    /**
     * Remove the specified listing
     */
    public function destroy(MarketplaceListing $listing)
    {
        // Check if the authenticated user owns this listing
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('marketplace.my-listings')
                ->with('error', 'You do not have permission to delete this listing.');
        }
        
        // Delete image if exists
        if ($listing->image) {
            Storage::disk('public')->delete($listing->image);
        }
        
        // Delete the listing
        $listing->delete();
        
        return redirect()->route('marketplace.my-listings')
            ->with('success', 'Your listing has been successfully deleted.');
    }
    
    /**
     * Change the availability of a listing
     */
    public function updateStatus(Request $request, MarketplaceListing $listing)
    {
        // Check if the authenticated user owns this listing
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('marketplace.my-listings')
                ->with('error', 'You do not have permission to update this listing.');
        }
        
        $request->validate([
            'availability' => 'required|in:available,sold',
        ]);
        
        $listing->availability = $request->availability;
        $listing->save();
        
        return redirect()->route('marketplace.my-listings')
            ->with('success', 'Listing availability updated successfully.');
    }
}