<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Date range filter
        $startDate = $request->get('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Overview Statistics
        $overviewStats = $this->getOverviewStatistics($vendor);

        // Revenue Analytics
        $revenueAnalytics = $this->getRevenueAnalytics($vendor, $startDate, $endDate);

        // Application Trends
        $applicationTrends = $this->getApplicationTrends($vendor, $startDate, $endDate);

        // Resource Performance
        $resourcePerformance = $this->getResourcePerformance($vendor);

        // Geographic Distribution
        $geographicDistribution = $this->getGeographicDistribution($vendor);

        // Monthly Comparison
        $monthlyComparison = $this->getMonthlyComparison($vendor);

        return view('vendor.analytics.index', compact(
            'vendor',
            'overviewStats',
            'revenueAnalytics',
            'applicationTrends',
            'resourcePerformance',
            'geographicDistribution',
            'monthlyComparison',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStatistics($vendor)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        return [
            'total_resources' => $vendor->resources()->count(),
            'active_resources' => $vendor->resources()->where('status', 'active')->count(),
            'total_applications' => ResourceApplication::whereIn('resource_id', $resourceIds)->count(),
            'pending_applications' => ResourceApplication::whereIn('resource_id', $resourceIds)
                ->where('status', 'pending')->count(),
            'approved_applications' => ResourceApplication::whereIn('resource_id', $resourceIds)
                ->whereIn('status', ['approved', 'payment_pending', 'paid', 'fulfilled'])->count(),
            'fulfilled_applications' => ResourceApplication::whereIn('resource_id', $resourceIds)
                ->where('status', 'fulfilled')->count(),
            'total_revenue' => ResourceApplication::whereIn('resource_id', $resourceIds)
                ->where('status', 'paid')
                ->sum('amount_paid'),
            'expected_reimbursement' => $this->calculateExpectedReimbursement($vendor),
        ];
    }

    /**
     * Get revenue analytics
     */
    private function getRevenueAnalytics($vendor, $startDate, $endDate)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        $applications = ResourceApplication::whereIn('resource_id', $resourceIds)
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with('resource')
            ->get();

        return [
            'total_paid' => $applications->sum('amount_paid'),
            'total_quantity_sold' => $applications->sum('quantity_paid'),
            'average_order_value' => $applications->avg('amount_paid'),
            'payment_count' => $applications->count(),
            'expected_reimbursement' => $applications->sum(function($app) {
                return ($app->quantity_paid ?? 0) * ($app->resource->vendor_reimbursement ?? 0);
            }),
            'daily_revenue' => $applications->groupBy(function($app) {
                return $app->paid_at->format('Y-m-d');
            })->map(function($dayApps) {
                return [
                    'date' => $dayApps->first()->paid_at->format('M d'),
                    'amount' => $dayApps->sum('amount_paid'),
                    'count' => $dayApps->count(),
                ];
            })->values(),
        ];
    }

    /**
     * Get application trends
     */
    private function getApplicationTrends($vendor, $startDate, $endDate)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        $applications = ResourceApplication::whereIn('resource_id', $resourceIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('status', 'date')
            ->get();

        $dailyTrends = $applications->groupBy('date')->map(function($dayApps) {
            return [
                'date' => Carbon::parse($dayApps->first()->date)->format('M d'),
                'pending' => $dayApps->where('status', 'pending')->sum('count'),
                'approved' => $dayApps->whereIn('status', ['approved', 'payment_pending'])->sum('count'),
                'paid' => $dayApps->where('status', 'paid')->sum('count'),
                'fulfilled' => $dayApps->where('status', 'fulfilled')->sum('count'),
                'total' => $dayApps->sum('count'),
            ];
        })->values();

        return [
            'daily_trends' => $dailyTrends,
            'status_distribution' => ResourceApplication::whereIn('resource_id', $resourceIds)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
        ];
    }

    /**
     * Get resource performance
     */
    private function getResourcePerformance($vendor)
    {
        return $vendor->resources()
            ->withCount([
                'applications as total_applications',
                'applications as paid_applications' => fn($q) => $q->where('status', 'paid'),
                'applications as fulfilled_applications' => fn($q) => $q->where('status', 'fulfilled'),
            ])
            ->with(['applications' => function($q) {
                $q->where('status', 'paid')->select('resource_id', 'amount_paid', 'quantity_paid');
            }])
            ->get()
            ->map(function($resource) {
                $paidApps = $resource->applications;
                return [
                    'name' => $resource->name,
                    'type' => $resource->type,
                    'status' => $resource->status,
                    'total_applications' => $resource->total_applications,
                    'paid_applications' => $resource->paid_applications,
                    'fulfilled_applications' => $resource->fulfilled_applications,
                    'total_revenue' => $paidApps->sum('amount_paid'),
                    'quantity_sold' => $paidApps->sum('quantity_paid'),
                    'conversion_rate' => $resource->total_applications > 0 
                        ? round(($resource->paid_applications / $resource->total_applications) * 100, 2) 
                        : 0,
                    'available_stock' => $resource->available_stock,
                    'utilization_rate' => $resource->utilization_rate,
                ];
            })
            ->sortByDesc('total_revenue')
            ->values();
    }

    /**
     * Get geographic distribution
     */
    private function getGeographicDistribution($vendor)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        return ResourceApplication::whereIn('resource_id', $resourceIds)
            ->whereHas('farmer.lga')
            ->with('farmer.lga')
            ->get()
            ->groupBy('farmer.lga.name')
            ->map(function($lgaApps, $lgaName) {
                $paidApps = $lgaApps->where('status', 'paid');
                return [
                    'lga' => $lgaName,
                    'total_applications' => $lgaApps->count(),
                    'paid_applications' => $paidApps->count(),
                    'total_revenue' => $paidApps->sum('amount_paid'),
                ];
            })
            ->sortByDesc('total_applications')
            ->values();
    }

    /**
     * Get monthly comparison
     */
    private function getMonthlyComparison($vendor)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        $last6Months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $applications = ResourceApplication::whereIn('resource_id', $resourceIds)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->get();

            $paidApps = $applications->where('status', 'paid');

            $last6Months->push([
                'month' => $month->format('M Y'),
                'applications' => $applications->count(),
                'paid' => $paidApps->count(),
                'revenue' => $paidApps->sum('amount_paid'),
            ]);
        }

        return $last6Months;
    }

    /**
     * Calculate expected reimbursement
     */
    private function calculateExpectedReimbursement($vendor)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        return ResourceApplication::whereIn('resource_id', $resourceIds)
            ->where('status', 'paid')
            ->with('resource')
            ->get()
            ->sum(function($app) {
                return ($app->quantity_paid ?? 0) * ($app->resource->vendor_reimbursement ?? 0);
            });
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->get('end_date', Carbon::now());

        $resourceIds = $vendor->resources()->pluck('id');

        $applications = ResourceApplication::whereIn('resource_id', $resourceIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['resource', 'user', 'farmer'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="vendor-analytics-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Date', 'Resource', 'Farmer', 'Quantity', 'Amount Paid', 
                'Status', 'Payment Reference', 'Reimbursement Expected'
            ]);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->created_at->format('Y-m-d'),
                    $app->resource->name,
                    $app->user->name,
                    $app->quantity_paid ?? 'N/A',
                    $app->amount_paid ?? 0,
                    ucfirst($app->status),
                    $app->payment_reference ?? 'N/A',
                    ($app->quantity_paid ?? 0) * ($app->resource->vendor_reimbursement ?? 0),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}