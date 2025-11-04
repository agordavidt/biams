<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\LGA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CooperativeViewController extends Controller
{
    /**
     * Display a listing of all cooperatives in the state
     */

    public function index(Request $request)
    {
        // Base query with optimized relationships
        $query = Cooperative::with([
            'lga:id,name',
            'registeredBy:id,name',
        ])->withCount([
            'members as total_members_count',
            'primaryFarmers as primary_farmers_count'
        ]);

        // Add active members count using direct subquery (most reliable approach)
        $query->addSelect([
            'active_members_count' => function($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from('cooperative_farmer')
                    ->whereColumn('cooperative_farmer.cooperative_id', 'cooperatives.id')
                    ->where('cooperative_farmer.membership_status', 'active');
            }
        ]);

        // Search functionality with optimized indexing
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('cooperatives.name', 'like', "%{$search}%")
                ->orWhere('cooperatives.registration_number', 'like', "%{$search}%")
                ->orWhere('cooperatives.contact_person', 'like', "%{$search}%")
                ->orWhere('cooperatives.email', 'like', "%{$search}%")
                ->orWhereHas('lga', function($lgaQuery) use ($search) {
                    $lgaQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        // LGA filter
        if ($request->filled('lga_id')) {
            $query->where('lga_id', $request->lga_id);
        }

        // Activity filter with JSON column handling
        if ($request->filled('activity')) {
            $query->whereJsonContains('primary_activities', $request->activity);
        }

        // Sort options with validation
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        // Validate sort columns to prevent SQL injection
        $allowedSorts = ['created_at', 'name', 'total_member_count', 'total_land_size'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'created_at';
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'desc';
        
        $query->orderBy($sort, $direction);

        $cooperatives = $query->paginate(20)->withQueryString();

        // Get all LGAs for filter dropdown (cached for performance)
        $lgas = Cache::remember('lgas_list', 3600, function () {
            return LGA::orderBy('name')->get(['id', 'name']);
        });

        // State-wide statistics (cached for better performance)
        $stats = Cache::remember('state_cooperative_stats', 300, function () {
            $totalCooperatives = Cooperative::count();
            $totalMembers = DB::table('cooperative_farmer')->count();
            $activeMembers = DB::table('cooperative_farmer')
                ->where('membership_status', 'active')
                ->count();
            $totalLand = Cooperative::sum('total_land_size');
            
            return [
                'total_cooperatives' => $totalCooperatives,
                'total_members' => $totalMembers,
                'active_members' => $activeMembers,
                'total_land_managed' => $totalLand,
                'cooperatives_with_land' => Cooperative::where('total_land_size', '>', 0)->count(),
                'average_members_per_cooperative' => $totalCooperatives > 0 ? $totalMembers / $totalCooperatives : 0,
                'member_activity_rate' => $totalMembers > 0 ? ($activeMembers / $totalMembers) * 100 : 0,
            ];
        });

        // LGA-wise distribution (cached)
        $lgaDistribution = Cache::remember('lga_cooperative_distribution', 300, function () {
            return Cooperative::select('lga_id', DB::raw('count(*) as count'))
                ->with('lga:id,name')
                ->groupBy('lga_id')
                ->orderBy('count', 'desc')
                ->get();
        });

        // Activity distribution for filters
        $activityOptions = Cache::remember('cooperative_activity_options', 3600, function () {
            return [
                'Input Procurement',
                'Output Marketing',
                'Processing',
                'Storage',
                'Transportation',
                'Credit/Finance',
                'Training/Extension',
                'Equipment Sharing',
                'Bulk Purchasing',
                'Market Linkage',
            ];
        });

        return view('admin.cooperatives.index', compact(
            'cooperatives',
            'lgas',
            'stats',
            'lgaDistribution',
            'activityOptions'
        ));
    }

    /**
     * Display the specified cooperative with detailed information
     */
    public function show(Cooperative $cooperative)
    {
        $cooperative->load([
            'lga:id,name,code',
            'registeredBy:id,name,email',
            'members' => function($query) {
                $query->select('farmers.id', 'farmers.full_name', 'farmers.phone_primary', 'farmers.status', 'farmers.ward')
                    ->withPivot('membership_number', 'joined_date', 'membership_status', 'position')
                    ->orderBy('cooperative_farmer.joined_date', 'desc');
            },
            'primaryFarmers:id,full_name,phone_primary,status'
        ]);

        // Member statistics
        $memberStats = [
            'total_members' => $cooperative->members->count(),
            'active_members' => $cooperative->members->where('pivot.membership_status', 'active')->count(),
            'inactive_members' => $cooperative->members->where('pivot.membership_status', 'inactive')->count(),
            'leadership_positions' => $cooperative->members->where('pivot.position')->count(),
        ];

        // Activity breakdown
        $activities = $cooperative->primary_activities ?? [];
        
        // Cooperative performance metrics
        $performance = [
            'member_retention_rate' => $memberStats['total_members'] > 0 ? 
                ($memberStats['active_members'] / $memberStats['total_members']) * 100 : 0,
            'land_per_member' => $memberStats['active_members'] > 0 ? 
                $cooperative->total_land_size / $memberStats['active_members'] : 0,
        ];

        return view('admin.cooperatives.show', compact(
            'cooperative',
            'memberStats',
            'activities',
            'performance'
        ));
    }

    /**
     * Export cooperatives data
     */
    public function export(Request $request)
    {
        // Implementation for Excel/PDF export
        // You can use Laravel Excel package here
        
        return response()->json([
            'message' => 'Export functionality will be implemented soon',
            'filters' => $request->all()
        ]);
    }

    /**
     * Get activity options for filters
     */
    private function getActivityOptions(): array
    {
        return [
            'Input Procurement',
            'Output Marketing',
            'Processing',
            'Storage',
            'Transportation',
            'Credit/Finance',
            'Training/Extension',
            'Equipment Sharing',
            'Bulk Purchasing',
            'Market Linkage',
        ];
    }
}