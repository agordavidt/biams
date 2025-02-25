<?php

namespace App\Http\Controllers;

use App\Models\Market\MarketplaceCategory;
use App\Models\Market\MarketplaceListing;
use Illuminate\Http\Request;

class MarketplaceVisitorController extends Controller
{
    /**
     * Display the marketplace for visitors (non-authenticated users)
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
        
        return view('visitor.marketplace.index', compact('listings', 'categories'));
    }
    
    /**
     * Display the specified listing details for visitors
     */
    public function show(MarketplaceListing $listing)
    {
        // Check if the listing is active and not expired
        if ($listing->availability !== 'available' || $listing->expires_at < now()) {
            return redirect()->route('visitor.marketplace')
                ->with('error', 'This listing is no longer available.');
        }
        
        // Load seller info for display (only public info)
        $seller = $listing->user;
        
        // Get similar listings (same category, excluding this one)
        $similarListings = MarketplaceListing::where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->where('availability', 'available')
            ->where('expires_at', '>', now())
            ->latest()
            ->take(4)
            ->get();
        
        return view('visitor.marketplace.show', compact('listing', 'seller', 'similarListings'));
    }
}