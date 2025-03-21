<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market\MarketplaceCategory;
use App\Models\Market\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MarketplaceAdminController extends Controller
{
    /**
     * Display dashboard with marketplace statistics
     */
    public function dashboard()
    {
        $totalListings = MarketplaceListing::count();
        $activeListings = MarketplaceListing::where('availability', 'available')->where('expires_at', '>', now())->count();
        $soldListings = MarketplaceListing::where('availability', 'sold')->count();
        $expiredListings = MarketplaceListing::where('expires_at', '<', now())->count();
        
        $categoryCounts = MarketplaceCategory::withCount('listings')->get();
        
        $recentListings = MarketplaceListing::with('user', 'category')
            ->latest()
            ->take(10)
            ->get();
            
        return view('admin.marketplace.dashboard', compact(
            'totalListings', 
            'activeListings', 
            'soldListings', 
            'expiredListings', 
            'categoryCounts', 
            'recentListings'
        ));
    }
    
    /**
     * Display all marketplace listings with management options
     */
    public function listings(Request $request)
    {
        $query = MarketplaceListing::with(['user', 'category']);
        
        // Apply filters
        if ($request->has('availability')) {
            $query->where('availability', $request->availability);
        }
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $listings = $query->latest()->paginate(20);
        $categories = MarketplaceCategory::all();
        
        return view('admin.marketplace.listings', compact('listings', 'categories'));
    }
    
    /**
     * Remove a listing (admin function)
     */
    public function removeListing(MarketplaceListing $listing)
    {
        // Delete image if exists
        if ($listing->image) {
            Storage::disk('public')->delete($listing->image);
        }
        
        $listing->delete();
        
        return back()->with('success', 'Listing removed successfully.');
    }
    
    /**
     * Manage categories
     */
    public function categories()
    {
        $categories = MarketplaceCategory::withCount('listings')->get();
        return view('admin.marketplace.categories', compact('categories'));
    }
    
    /**
     * Store a new category
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:marketplace_categories,name',
            'description' => 'nullable|string',
        ]);
        
        $category = new MarketplaceCategory();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->is_active = true;
        $category->save();
        
        return back()->with('success', 'Category created successfully.');
    }
    
    /**
     * Update a category
     */
    public function updateCategory(Request $request, MarketplaceCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:marketplace_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->is_active = $request->is_active ?? false;
        $category->save();
        
        return back()->with('success', 'Category updated successfully.');
    }
    
    /**
     * Delete a category
     */
    public function deleteCategory(MarketplaceCategory $category)
    {
        // Check if category has listings
        if ($category->listings()->count() > 0) {
            return back()->with('error', 'Cannot delete category with active listings.');
        }
        
        $category->delete();
        
        return back()->with('success', 'Category deleted successfully.');
    }
}


