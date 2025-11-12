<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourcesController extends Controller
{
    /**
     * Resources overview for Governor
     */
    public function index(Request $request)
    {
        // Date range for filtering
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // Overall statistics
        $stats = [
            'total_resources' => Resource::count(),
            'active_resources' => Resource::where('status', 'active')->count(),
            'ministry_resources' => Resource::ministryResources()->count(),
            'vendor_resources' => Resource::vendorResources()->count(),
            'total_applications' => ResourceApplication::count(),
            'pending_applications' => ResourceApplication::where('status', 'pending')->count(),
            'fulfilled_applications' => ResourceApplication::where('status', 'fulfilled')->count(),
            'total_beneficiaries' => ResourceApplication::whereIn('status', ['fulfilled', 'paid'])->distinct('user_id')->count(),
            'total_value_distributed' => ResourceApplication::whereIn('status', ['paid', 'fulfilled'])->sum('amount_paid'),
        ];

        // Resource distribution by type
        $resourcesByType = Resource::where('status', 'active')
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->map(function($item) {
                return [
                    'type' => ucfirst($item->type),
                    'count' => $item->count
                ];
            });

        // Application trends (last 30 days)
        $applicationTrends = ResourceApplication::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "fulfilled" THEN 1 ELSE 0 END) as fulfilled')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing resources
        $topResources = Resource::withCount([
            'applications',
            'applications as fulfilled_count' => fn($q) => $q->where('status', 'fulfilled')
        ])
        ->having('applications_count', '>', 0)
        ->orderByDesc('fulfilled_count')
        ->limit(10)
        ->get();

        // Distribution efficiency by LGA
        $lgaDistribution = DB::table('resource_applications')
            ->join('users', 'resource_applications.user_id', '=', 'users.id')
            ->join('farmers', 'users.id', '=', 'farmers.user_id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga_name',
                DB::raw('COUNT(DISTINCT resource_applications.id) as total_applications'),
                DB::raw('COUNT(DISTINCT CASE WHEN resource_applications.status = "fulfilled" THEN resource_applications.id END) as fulfilled'),
                DB::raw('SUM(CASE WHEN resource_applications.status IN ("paid", "fulfilled") THEN resource_applications.amount_paid ELSE 0 END) as total_value')
            )
            ->groupBy('lgas.id', 'lgas.name')
            ->orderByDesc('total_applications')
            ->limit(10)
            ->get();

        // Payment vs Free resources impact
        $paymentAnalysis = [
            'paid' => [
                'count' => Resource::paid()->count(),
                'applications' => ResourceApplication::whereHas('resource', fn($q) => $q->paid())->count(),
                'beneficiaries' => ResourceApplication::whereHas('resource', fn($q) => $q->paid())
                    ->whereIn('status', ['paid', 'fulfilled'])
                    ->distinct('user_id')
                    ->count(),
            ],
            'free' => [
                'count' => Resource::free()->count(),
                'applications' => ResourceApplication::whereHas('resource', fn($q) => $q->free())->count(),
                'beneficiaries' => ResourceApplication::whereHas('resource', fn($q) => $q->free())
                    ->whereIn('status', ['fulfilled'])
                    ->distinct('user_id')
                    ->count(),
            ]
        ];

        return view('governor.resources.index', compact(
            'stats',
            'resourcesByType',
            'applicationTrends',
            'topResources',
            'lgaDistribution',
            'paymentAnalysis',
            'dateFrom',
            'dateTo'
        ));
    }
}