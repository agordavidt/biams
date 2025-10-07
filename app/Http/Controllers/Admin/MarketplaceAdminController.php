<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market\MarketplaceCategory;
use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceSubscription;
use App\Models\Market\MarketplaceInquiry;
use App\Models\LGA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MarketplaceAdminController extends Controller
{
    /**
     * Display marketplace dashboard with key metrics.
     */
    public function dashboard()
    {
        $stats = [
            'total_listings' => MarketplaceListing::count(),
            'active_listings' => MarketplaceListing::active()->count(),
            'pending_review' => MarketplaceListing::pendingReview()->count(),
            'expired_listings' => MarketplaceListing::expired()->count(),
            'total_subscriptions' => MarketplaceSubscription::where('status', 'paid')->count(),
            'active_subscriptions' => MarketplaceSubscription::active()->count(),
            'expiring_soon' => MarketplaceSubscription::active()
                ->whereBetween('end_date', [now(), now()->addDays(30)])
                ->count(),
            'total_inquiries' => MarketplaceInquiry::count(),
            'new_inquiries' => MarketplaceInquiry::where('status', 'new')->count(),
            'revenue_this_month' => MarketplaceSubscription::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'revenue_total' => MarketplaceSubscription::where('status', 'paid')->sum('amount'),
        ];

        // Category distribution
        $categoryStats = MarketplaceCategory::withCount(['listings' => function($query) {
            $query->active();
        }])->get();

        // LGA distribution
        $lgaStats = MarketplaceListing::select('location', DB::raw('count(*) as count'))
            ->active()
            ->groupBy('location')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Recent activity
        $recentListings = MarketplaceListing::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $recentSubscriptions = MarketplaceSubscription::with('user')
            ->where('status', 'paid')
            ->latest('paid_at')
            ->take(10)
            ->get();

        // Monthly trends (last 6 months)
        $monthlyTrends = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyTrends->push([
                'month' => $date->format('M Y'),
                'listings' => MarketplaceListing::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'subscriptions' => MarketplaceSubscription::where('status', 'paid')
                    ->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month)
                    ->count(),
                'revenue' => MarketplaceSubscription::where('status', 'paid')
                    ->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month)
                    ->sum('amount'),
            ]);
        }

        return view('admin.marketplace.dashboard', compact(
            'stats',
            'categoryStats',
            'lgaStats',
            'recentListings',
            'recentSubscriptions',
            'monthlyTrends'
        ));
    }

    /**
     * Display all listings with filtering.
     */
    public function listings(Request $request)
    {
        $query = MarketplaceListing::with(['user', 'category', 'approvedBy'])
            ->withCount('inquiries');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show pending and active
            $query->whereIn('status', ['pending_review', 'active']);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_high':
                $query->orderByDesc('price');
                break;
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'popular':
                $query->orderByDesc('view_count');
                break;
            default:
                $query->latest();
        }

        $listings = $query->paginate(20)->withQueryString();
        $categories = MarketplaceCategory::all();

        // Stats for filter display
        $statusCounts = [
            'all' => MarketplaceListing::count(),
            'pending_review' => MarketplaceListing::where('status', 'pending_review')->count(),
            'active' => MarketplaceListing::where('status', 'active')->count(),
            'expired' => MarketplaceListing::where('status', 'expired')->count(),
            'rejected' => MarketplaceListing::where('status', 'rejected')->count(),
        ];

        return view('admin.marketplace.listings', compact('listings', 'categories', 'statusCounts'));
    }

    /**
     * Approve a pending listing.
     */
    public function approveListing(MarketplaceListing $listing)
    {
        $this->authorize('approve', $listing);

        if ($listing->status !== 'pending_review') {
            return back()->with('error', 'Only pending listings can be approved.');
        }

        try {
            $listing->approve(auth()->user());

            // TODO: Send notification to farmer
            // Mail::to($listing->user->email)->send(new ListingApproved($listing));

            return back()->with('success', 'Listing approved successfully and is now live on the marketplace.');
        } catch (\Exception $e) {
            Log::error('Listing approval error: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve listing. Please try again.');
        }
    }

    /**
     * Reject a pending listing.
     */
    public function rejectListing(Request $request, MarketplaceListing $listing)
    {
        $this->authorize('reject', $listing);

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($listing->status !== 'pending_review') {
            return back()->with('error', 'Only pending listings can be rejected.');
        }

        try {
            $listing->reject($request->rejection_reason);

            // TODO: Send notification to farmer
            // Mail::to($listing->user->email)->send(new ListingRejected($listing));

            return back()->with('success', 'Listing rejected. The farmer has been notified and can resubmit after corrections.');
        } catch (\Exception $e) {
            Log::error('Listing rejection error: ' . $e->getMessage());
            return back()->with('error', 'Failed to reject listing. Please try again.');
        }
    }

    /**
     * Remove a listing (admin delete).
     */
    public function removeListing(MarketplaceListing $listing)
    {
        $this->authorize('delete', $listing);

        DB::beginTransaction();
        try {
            // Delete all associated images
            foreach ($listing->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                if ($image->thumbnail_path) {
                    Storage::disk('public')->delete($image->thumbnail_path);
                }
            }

            $listing->delete();

            DB::commit();

            return back()->with('success', 'Listing removed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Listing removal error: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove listing. Please try again.');
        }
    }

    /**
     * Display category management.
     */
    public function categories()
    {
        $categories = MarketplaceCategory::withCount(['listings', 'activeListings'])
            ->orderBy('display_order')
            ->get();

        return view('admin.marketplace.categories', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:marketplace_categories,name',
            'description' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
        ]);

        try {
            MarketplaceCategory::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'display_order' => $request->display_order ?? 0,
                'is_active' => true,
            ]);

            return back()->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            Log::error('Category creation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Update a category.
     */
    public function updateCategory(Request $request, MarketplaceCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:marketplace_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'display_order' => $request->display_order ?? $category->display_order,
                'is_active' => $request->has('is_active'),
            ]);

            return back()->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            Log::error('Category update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(MarketplaceCategory $category)
    {
        if ($category->listings()->exists()) {
            return back()->with('error', 'Cannot delete category with existing listings. Please reassign or delete listings first.');
        }

        try {
            $category->delete();
            return back()->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Category deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete category. Please try again.');
        }
    }

    /**
     * Display subscription management.
     */
    public function subscriptions(Request $request)
    {
        $query = MarketplaceSubscription::with('user');

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'expired') {
                $query->expired();
            } else {
                $query->where('status', $request->status);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_reference', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $subscriptions = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total_paid' => MarketplaceSubscription::where('status', 'paid')->count(),
            'active' => MarketplaceSubscription::active()->count(),
            'expiring_soon' => MarketplaceSubscription::active()
                ->whereBetween('end_date', [now(), now()->addDays(30)])
                ->count(),
            'expired' => MarketplaceSubscription::expired()->count(),
        ];

        return view('admin.marketplace.subscriptions', compact('subscriptions', 'stats'));
    }

    /**
     * Export subscriptions data.
     */
    public function exportSubscriptions(Request $request)
    {
        // TODO: Implement CSV/Excel export
        return back()->with('info', 'Export functionality coming soon.');
    }

    /**
     * Display marketplace analytics.
     */
    public function analytics(Request $request)
    {
        $dateRange = $request->get('range', '30'); // days
        $startDate = now()->subDays($dateRange);

        $analytics = [
            'listings' => [
                'total' => MarketplaceListing::where('created_at', '>=', $startDate)->count(),
                'approved' => MarketplaceListing::where('approved_at', '>=', $startDate)->count(),
                'by_category' => MarketplaceCategory::withCount(['listings' => function($q) use ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }])->get(),
                'by_status' => MarketplaceListing::select('status', DB::raw('count(*) as count'))
                    ->where('created_at', '>=', $startDate)
                    ->groupBy('status')
                    ->get(),
            ],
            'inquiries' => [
                'total' => MarketplaceInquiry::where('created_at', '>=', $startDate)->count(),
                'conversion_rate' => $this->calculateConversionRate($startDate),
                'by_listing' => MarketplaceInquiry::select('listing_id', DB::raw('count(*) as count'))
                    ->where('created_at', '>=', $startDate)
                    ->groupBy('listing_id')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->with('listing')
                    ->get(),
            ],
            'subscriptions' => [
                'new' => MarketplaceSubscription::where('status', 'paid')
                    ->where('paid_at', '>=', $startDate)
                    ->count(),
                'revenue' => MarketplaceSubscription::where('status', 'paid')
                    ->where('paid_at', '>=', $startDate)
                    ->sum('amount'),
            ],
            'top_farmers' => $this->getTopFarmers($startDate),
        ];

        return view('admin.marketplace.analytics', compact('analytics', 'dateRange'));
    }

    /**
     * Export marketplace report.
     */
    public function exportReport(Request $request)
    {
        // TODO: Implement comprehensive report export
        return back()->with('info', 'Report export functionality coming soon.');
    }

    /**
     * Calculate inquiry to conversion rate.
     */
    private function calculateConversionRate($startDate)
    {
        $totalInquiries = MarketplaceInquiry::where('created_at', '>=', $startDate)->count();
        if ($totalInquiries === 0) {
            return 0;
        }

        $converted = MarketplaceInquiry::where('created_at', '>=', $startDate)
            ->where('status', 'converted')
            ->count();

        return round(($converted / $totalInquiries) * 100, 2);
    }

    /**
     * Get top performing farmers.
     */
    private function getTopFarmers($startDate)
    {
        return MarketplaceListing::select('user_id', 
                DB::raw('count(*) as listings_count'),
                DB::raw('sum(view_count) as total_views'),
                DB::raw('sum(inquiry_count) as total_inquiries'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('user_id')
            ->orderByDesc('total_inquiries')
            ->limit(10)
            ->with('user')
            ->get();
    }
}