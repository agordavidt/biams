<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    /**
     * Display all resources in the system
     */
    public function index(Request $request)
    {
        $query = Resource::with(['vendor', 'createdBy', 'reviewedBy'])
            ->withCount('applications');

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('vendor', function($vq) use ($search) {
                      $vq->where('legal_name', 'like', "%$search%");
                  });
            });
        }

        // Filter by source
        if ($request->source === 'ministry') {
            $query->ministryResources();
        } elseif ($request->source === 'vendor') {
            $query->vendorResources();
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by payment type
        if ($request->payment_type === 'paid') {
            $query->paid();
        } elseif ($request->payment_type === 'free') {
            $query->free();
        }

        $resources = $query->latest()->paginate(20)->withQueryString();

        // Summary statistics
        $stats = [
            'total' => Resource::count(),
            'ministry' => Resource::ministryResources()->count(),
            'vendor' => Resource::vendorResources()->count(),
            'active' => Resource::where('status', 'active')->count(),
            'proposed' => Resource::where('status', 'proposed')->count(),
            'under_review' => Resource::where('status', 'under_review')->count(),
            'rejected' => Resource::where('status', 'rejected')->count(),
            'paid' => Resource::paid()->count(),
            'free' => Resource::free()->count(),
            'total_applications' => DB::table('resource_applications')->count(),
        ];

        return view('super_admin.resources.index', compact('resources', 'stats'));
    }

    /**
     * Display resource details
     */
    public function show(Resource $resource)
    {
        $resource->load([
            'vendor', 
            'createdBy', 
            'reviewedBy',
            'applications' => function($query) {
                $query->latest()->limit(10);
            },
            'applications.user',
            'applications.farmer'
        ]);

        // Resource statistics
        $stats = [
            'total_applications' => $resource->applications()->count(),
            'pending' => $resource->applications()->where('status', 'pending')->count(),
            'approved' => $resource->applications()->where('status', 'approved')->count(),
            'paid' => $resource->applications()->where('status', 'paid')->count(),
            'fulfilled' => $resource->applications()->where('status', 'fulfilled')->count(),
            'rejected' => $resource->applications()->where('status', 'rejected')->count(),
            'total_revenue' => $resource->applications()
                ->whereIn('status', ['paid', 'fulfilled'])
                ->sum('amount_paid'),
        ];

        return view('super_admin.resources.show', compact('resource', 'stats'));
    }

    /**
     * Display resource applications
     */
    public function applications(Resource $resource, Request $request)
    {
        $query = $resource->applications()
            ->with(['user', 'farmer', 'reviewedBy', 'fulfilledBy']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        return view('super_admin.resources.applications', compact('resource', 'applications'));
    }

    /**
     * Analytics overview
     */
    public function analytics(Request $request)
    {
        // Date range
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // Resource distribution by type
        $resourcesByType = Resource::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // Top resources by applications
        $topResources = Resource::withCount('applications')
            ->orderByDesc('applications_count')
            ->limit(10)
            ->get();

        // Vendor performance
        $vendorPerformance = Vendor::withCount([
            'resources',
            'resources as active_resources' => fn($q) => $q->where('status', 'active'),
        ])
        ->with(['resources' => function($q) {
            $q->withCount('applications');
        }])
        ->get()
        ->map(function($vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->legal_name,
                'total_resources' => $vendor->resources_count,
                'active_resources' => $vendor->active_resources,
                'total_applications' => $vendor->resources->sum('applications_count'),
            ];
        })
        ->sortByDesc('total_applications')
        ->take(10);

        // Applications over time
        $applicationsOverTime = DB::table('resource_applications')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution
        $statusDistribution = Resource::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('super_admin.resources.analytics', compact(
            'resourcesByType',
            'topResources',
            'vendorPerformance',
            'applicationsOverTime',
            'statusDistribution',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Export resources to CSV
     */
    public function export(Request $request)
    {
        $resources = Resource::query()
            ->with(['vendor', 'createdBy', 'reviewedBy'])
            ->withCount('applications')
            ->when($request->source === 'ministry', fn($q) => $q->ministryResources())
            ->when($request->source === 'vendor', fn($q) => $q->vendorResources())
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->type, fn($q, $type) => $q->where('type', $type))
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="resources-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($resources) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Name', 'Type', 'Source', 'Vendor', 'Status', 
                'Payment Required', 'Price', 'Stock', 'Available Stock',
                'Total Applications', 'Created By', 'Created At'
            ]);

            foreach ($resources as $resource) {
                fputcsv($file, [
                    $resource->id,
                    $resource->name,
                    ucfirst($resource->type),
                    $resource->vendor_id ? 'Vendor' : 'Ministry',
                    $resource->vendor ? $resource->vendor->legal_name : 'Ministry',
                    ucfirst($resource->status),
                    $resource->requires_payment ? 'Yes' : 'No',
                    $resource->price ?? 0,
                    $resource->total_stock ?? 'N/A',
                    $resource->available_stock ?? 'N/A',
                    $resource->applications_count,
                    $resource->createdBy ? $resource->createdBy->name : 'N/A',
                    $resource->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
