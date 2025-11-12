<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorsController extends Controller
{
    /**
     * Vendors overview for Governor
     */
    public function index(Request $request)
    {
        // Date range for filtering
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // Overall statistics
        $stats = [
            'total_vendors' => Vendor::count(),
            'active_vendors' => Vendor::where('is_active', true)->count(),
            'total_resources' => Resource::vendorResources()->count(),
            'active_resources' => Resource::vendorResources()->where('status', 'active')->count(),
            'pending_review' => Resource::vendorResources()->where('status', 'proposed')->count(),
            'total_applications' => ResourceApplication::whereHas('resource', fn($q) => $q->vendorResources())->count(),
            'fulfilled_applications' => ResourceApplication::whereHas('resource', fn($q) => $q->vendorResources())
                ->where('status', 'fulfilled')
                ->count(),
            'beneficiaries_served' => ResourceApplication::whereHas('resource', fn($q) => $q->vendorResources())
                ->whereIn('status', ['paid', 'fulfilled'])
                ->distinct('user_id')
                ->count(),
        ];

        // Vendor performance ranking
        $vendorPerformance = Vendor::withCount([
            'resources',
            'resources as active_resources' => fn($q) => $q->where('status', 'active'),
        ])
        ->with(['resources' => function($q) use ($dateFrom, $dateTo) {
            $q->withCount([
                'applications as total_applications',
                'applications as fulfilled_applications' => fn($aq) => $aq->where('status', 'fulfilled')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]),
                'applications as beneficiaries' => fn($aq) => $aq->whereIn('status', ['paid', 'fulfilled'])
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
            ]);
        }])
        ->get()
        ->map(function($vendor) {
            $totalApplications = $vendor->resources->sum('total_applications');
            $fulfilledApplications = $vendor->resources->sum('fulfilled_applications');
            
            return [
                'id' => $vendor->id,
                'name' => $vendor->legal_name,
                'organization_type' => ucfirst(str_replace('_', ' ', $vendor->organization_type)),
                'total_resources' => $vendor->resources_count,
                'active_resources' => $vendor->active_resources,
                'total_applications' => $totalApplications,
                'fulfilled_applications' => $fulfilledApplications,
                'fulfillment_rate' => $totalApplications > 0 ? round(($fulfilledApplications / $totalApplications) * 100, 1) : 0,
                'is_active' => $vendor->is_active,
            ];
        })
        ->sortByDesc('fulfilled_applications')
        ->take(15);

        // Vendor distribution by organization type
        $vendorsByType = Vendor::select('organization_type', DB::raw('count(*) as count'))
            ->groupBy('organization_type')
            ->get()
            ->map(function($item) {
                return [
                    'type' => ucfirst(str_replace('_', ' ', $item->organization_type)),
                    'count' => $item->count
                ];
            });

        // Focus areas distribution
        $focusAreasData = Vendor::whereNotNull('focus_areas')->get()
            ->pluck('focus_areas')
            ->flatten()
            ->countBy()
            ->map(function($count, $area) {
                return [
                    'area' => ucfirst(str_replace('_', ' ', $area)),
                    'count' => $count
                ];
            })
            ->sortByDesc('count')
            ->values();

        // Geographic coverage (vendors per LGA based on beneficiaries)
        $geographicCoverage = DB::table('resource_applications')
            ->join('resources', 'resource_applications.resource_id', '=', 'resources.id')
            ->join('vendors', 'resources.vendor_id', '=', 'vendors.id')
            ->join('users', 'resource_applications.user_id', '=', 'users.id')
            ->join('farmers', 'users.id', '=', 'farmers.user_id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->whereIn('resource_applications.status', ['paid', 'fulfilled'])
            ->whereBetween('resource_applications.created_at', [$dateFrom, $dateTo])
            ->select(
                'lgas.name as lga_name',
                DB::raw('COUNT(DISTINCT vendors.id) as vendor_count'),
                DB::raw('COUNT(DISTINCT resource_applications.user_id) as beneficiaries')
            )
            ->groupBy('lgas.id', 'lgas.name')
            ->orderByDesc('beneficiaries')
            ->limit(10)
            ->get();

        // Vendor impact comparison (Ministry vs Vendor)
        $impactComparison = [
            'ministry' => [
                'resources' => Resource::ministryResources()->where('status', 'active')->count(),
                'applications' => ResourceApplication::whereHas('resource', fn($q) => $q->ministryResources())
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count(),
                'fulfilled' => ResourceApplication::whereHas('resource', fn($q) => $q->ministryResources())
                    ->where('status', 'fulfilled')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count(),
                'beneficiaries' => ResourceApplication::whereHas('resource', fn($q) => $q->ministryResources())
                    ->whereIn('status', ['fulfilled', 'paid'])
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->distinct('user_id')
                    ->count(),
            ],
            'vendor' => [
                'resources' => Resource::vendorResources()->where('status', 'active')->count(),
                'applications' => ResourceApplication::whereHas('resource', fn($q) => $q->vendorResources())
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count(),
                'fulfilled' => ResourceApplication::whereHas('resource', fn($q) => $q->vendorResources())
                    ->where('status', 'fulfilled')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count(),
                'beneficiaries' => ResourceApplication::whereHas('resource', fn($q) => $q->vendorResources())
                    ->whereIn('status', ['fulfilled', 'paid'])
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->distinct('user_id')
                    ->count(),
            ]
        ];

        return view('governor.vendors.index', compact(
            'stats',
            'vendorPerformance',
            'vendorsByType',
            'focusAreasData',
            'geographicCoverage',
            'impactComparison',
            'dateFrom',
            'dateTo'
        ));
    }
}