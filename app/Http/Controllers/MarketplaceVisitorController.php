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
        // Get all active categories for filtering
        $categories = MarketplaceCategory::where('is_active', true)->get();

        // Build query for available and non-expired listings
        $query = MarketplaceListing::with(['category'])
            ->where('availability', 'available')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc'); // Latest listings first

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Apply location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        // Apply search filter (title or description)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Paginate results (12 per page)
        $listings = $query->paginate(12)->appends($request->query());

        // Return view with filtered listings and categories
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