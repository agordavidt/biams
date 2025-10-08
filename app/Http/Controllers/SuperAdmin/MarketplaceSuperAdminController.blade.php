<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Market\MarketplaceCategory;
use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceSubscription;
use App\Models\Market\MarketplaceInquiry;
use App\Models\LGA;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketplaceSuperAdminController extends Controller
{
    /**
     * Display comprehensive marketplace overview for SuperAdmin.
     */
    public function overview()
    {
        // System-wide statistics
        $stats = [
            'total_subscribers' => MarketplaceSubscription::where('status', 'paid')->distinct('user_id')->count(),
            'active_subscribers' => MarketplaceSubscription::active()->distinct('user_id')->count(),
            'total_revenue' => MarketplaceSubscription::where('status', 'paid')->sum('amount'),
            'monthly_revenue' => MarketplaceSubscription::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'total_listings' => MarketplaceListing::count(),
            'pending_approval' => MarketplaceListing::where('status', 'pending_review')->count(),
            'total_inquiries' => MarketplaceInquiry::count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];

        // LGA Performance Analysis
        $lgaPerformance = LGA::select('lgas.id', 'lgas.name')
            ->leftJoin('users', function($join) {
                $join->on('users.administrative_id', '=', 'lgas.id')
                     ->where('users.administrative_type', LGA::class);
            })
            ->leftJoin('marketplace_subscriptions', function($join) {
                $join->on('marketplace_subscriptions.user_id', '=', 'users.id')
                     ->where('marketplace_subscriptions.status', 'paid');
            })
            ->leftJoin('marketplace_listings', 'marketplace_listings.user_id', '=', 'users.id')
            ->leftJoin('marketplace_inquiries', 'marketplace_inquiries.listing_id', '=', 'marketplace_listings.id')
            ->groupBy('lgas.id', 'lgas.name')
            ->selectRaw('COUNT(DISTINCT marketplace_subscriptions.id) as subscribers_count')
            ->selectRaw('COUNT(DISTINCT marketplace_listings.id) as listings_count')
            ->selectRaw('COUNT(marketplace_inquiries.id) as inquiries_count')
            ->selectRaw('COALESCE(SUM(marketplace_subscriptions.amount), 0) as revenue')
            ->orderByDesc('revenue')
            ->get();

        // Category Distribution
        $categoryData = MarketplaceCategory::withCount(['listings' => function($query) {
            $query->where('status', 'active');
        }])
        ->get()
        ->map(function($cat) {
            return [
                'label' => $cat->name,
                'value' => $cat->listings_count
            ];
        });

        // System Health Alerts
        $alerts = [
            'expiring_subscriptions' => MarketplaceSubscription::active()
                ->whereBetween('end_date', [now(), now()->addDays(30)])
                ->count(),
            'pending_approvals' => MarketplaceListing::where('status', 'pending_review')->count(),
        ];

        // Recent Activity
        $recentSubscriptions = MarketplaceSubscription::with('user')
            ->where('status', 'paid')
            ->latest('paid_at')
            ->take(5)
            ->get();

        // Top Performing Farmers
        $topFarmers = User::select('users.id', 'users.name')
            ->join('marketplace_listings', 'marketplace_listings.user_id', '=', 'users.id')
            ->leftJoin('marketplace_inquiries', 'marketplace_inquiries.listing_id', '=', 'marketplace_listings.id')
            ->groupBy('users.id', 'users.name')
            ->selectRaw('COUNT(DISTINCT marketplace_listings.id) as listings_count')
            ->selectRaw('COUNT(marketplace_inquiries.id) as inquiries_count')
            ->selectRaw('SUM(marketplace_listings.view_count) as total_views')
            ->orderByDesc('inquiries_count')
            ->take(5)
            ->get();

        return view('super-admin.marketplace.overview', compact(
            'stats',
            'lgaPerformance',
            'categoryData',
            'alerts',
            'recentSubscriptions',
            'topFarmers'
        ));
    }

    /**
     * Calculate overall conversion rate.
     */
    private function calculateConversionRate()
    {
        $totalInquiries = MarketplaceInquiry::count();
        
        if ($totalInquiries === 0) {
            return 0;
        }

        $converted = MarketplaceInquiry::where('status', 'converted')->count();
        
        return round(($converted / $totalInquiries) * 100, 2);
    }

    /**
     * Generate comprehensive system report.
     */
    public function generateReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $report = [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'subscriptions' => [
                'new' => MarketplaceSubscription::where('status', 'paid')
                    ->whereBetween('paid_at', [$startDate, $endDate])
                    ->count(),
                'revenue' => MarketplaceSubscription::where('status', 'paid')
                    ->whereBetween('paid_at', [$startDate, $endDate])
                    ->sum('amount'),
            ],
            'listings' => [
                'created' => MarketplaceListing::whereBetween('created_at', [$startDate, $endDate])->count(),
                'approved' => MarketplaceListing::whereBetween('approved_at', [$startDate, $endDate])->count(),
            ],
            'inquiries' => [
                'received' => MarketplaceInquiry::whereBetween('created_at', [$startDate, $endDate])->count(),
                'conversion_rate' => $this->calculatePeriodConversionRate($startDate, $endDate),
            ],
        ];

        return view('super-admin.marketplace.report', compact('report'));
    }

    /**
     * Calculate conversion rate for a specific period.
     */
    private function calculatePeriodConversionRate($startDate, $endDate)
    {
        $totalInquiries = MarketplaceInquiry::whereBetween('created_at', [$startDate, $endDate])->count();
        
        if ($totalInquiries === 0) {
            return 0;
        }

        $converted = MarketplaceInquiry::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'converted')
            ->count();
        
        return round(($converted / $totalInquiries) * 100, 2);
    }

    /**
     * Bulk actions for system maintenance.
     */
    public function bulkActions(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'expire_old_listings':
                $expired = MarketplaceListing::where('status', 'active')
                    ->where('expires_at', '<', now())
                    ->update(['status' => 'expired']);
                
                return back()->with('success', "Marked {$expired} listings as expired.");

            case 'mark_expired_subscriptions':
                $expired = MarketplaceSubscription::where('status', 'paid')
                    ->where('end_date', '<', now())
                    ->update(['status' => 'expired']);
                
                return back()->with('success', "Updated {$expired} expired subscriptions.");

            default:
                return back()->with('error', 'Invalid action.');
        }
    }
}