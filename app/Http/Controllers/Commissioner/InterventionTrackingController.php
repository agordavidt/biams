<?php

namespace App\Http\Controllers\Commissioner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Intervention Tracking Controller
 * Monitors resource distribution and program effectiveness
 */
class InterventionTrackingController extends Controller
{
    public function index()
    {
        return view('commissioner.interventions.index');
    }

    /**
     * Get comprehensive beneficiary report for resources
     */
    public function getBeneficiaryReport(Request $request)
    {
        $filters = $request->validate([
            'resource_id' => 'nullable|exists:resources,id',
            'partner_id' => 'nullable|exists:partners,id',
            'status' => 'nullable|in:pending,granted,declined',
            'lga_id' => 'nullable|exists:lgas,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $query = DB::table('resource_applications')
            ->join('resources', 'resource_applications.resource_id', '=', 'resources.id')
            ->join('users', 'resource_applications.user_id', '=', 'users.id')
            ->leftJoin('farmers', 'users.id', '=', 'farmers.user_id')
            ->leftJoin('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->leftJoin('partners', 'resources.partner_id', '=', 'partners.id');

        // Apply filters
        if (isset($filters['resource_id'])) {
            $query->where('resource_applications.resource_id', $filters['resource_id']);
        }

        if (isset($filters['partner_id'])) {
            $query->where('resources.partner_id', $filters['partner_id']);
        }

        if (isset($filters['status'])) {
            $query->where('resource_applications.status', $filters['status']);
        }

        if (isset($filters['lga_id'])) {
            $query->where('farmers.lga_id', $filters['lga_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('resource_applications.created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('resource_applications.created_at', '<=', $filters['date_to']);
        }

        $totalApplications = (clone $query)->count();
        $grantedApplications = (clone $query)->where('resource_applications.status', 'granted')->count();
        
        $lgaBreakdown = (clone $query)
            ->select(
                'lgas.name as lga_name',
                DB::raw('COUNT(resource_applications.id) as total_applications'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as granted'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "declined" THEN 1 ELSE 0 END) as declined')
            )
            ->groupBy('lgas.name')
            ->orderByDesc('total_applications')
            ->get();

        $resourceBreakdown = (clone $query)
            ->select(
                'resources.name as resource_name',
                DB::raw('COUNT(resource_applications.id) as application_count'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as granted_count')
            )
            ->groupBy('resources.name')
            ->orderByDesc('application_count')
            ->get();

        return response()->json([
            'summary' => [
                'total_applications' => $totalApplications,
                'granted' => $grantedApplications,
                'pending' => (clone $query)->where('resource_applications.status', 'pending')->count(),
                'declined' => (clone $query)->where('resource_applications.status', 'declined')->count(),
                'success_rate' => $totalApplications > 0 ? round(($grantedApplications / $totalApplications) * 100, 2) : 0,
            ],
            'lga_breakdown' => $lgaBreakdown,
            'resource_breakdown' => $resourceBreakdown,
        ]);
    }

    /**
     * Track partner activities and impact
     */
    public function getPartnerActivities(Request $request)
    {
        $partnerId = $request->input('partner_id');

        $query = DB::table('partners')
            ->leftJoin('resources', 'partners.id', '=', 'resources.partner_id')
            ->leftJoin('resource_applications', 'resources.id', '=', 'resource_applications.resource_id')
            ->select(
                'partners.legal_name',
                'partners.organization_type',
                DB::raw('COUNT(DISTINCT resources.id) as total_resources'),
                DB::raw('COUNT(resource_applications.id) as total_applications'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as granted_applications')
            )
            ->groupBy('partners.id', 'partners.legal_name', 'partners.organization_type');

        if ($partnerId) {
            $query->where('partners.id', $partnerId);
        }

        $partnerStats = $query->get();

        // Get detailed resource performance for each partner
        $resourcePerformance = DB::table('resources')
            ->join('partners', 'resources.partner_id', '=', 'partners.id')
            ->leftJoin('resource_applications', 'resources.id', '=', 'resource_applications.resource_id')
            ->select(
                'partners.legal_name as partner_name',
                'resources.name as resource_name',
                DB::raw('COUNT(resource_applications.id) as application_count'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as success_count')
            )
            ->when($partnerId, function($q) use ($partnerId) {
                return $q->where('partners.id', $partnerId);
            })
            ->groupBy('partners.legal_name', 'resources.name')
            ->orderByDesc('application_count')
            ->get();

        return response()->json([
            'partner_stats' => $partnerStats,
            'resource_performance' => $resourcePerformance,
        ]);
    }

    /**
     * Analyze intervention reach and coverage gaps
     */
    public function getCoverageAnalysis()
    {
        // Total farmers per LGA
        $farmersByLga = DB::table('farmers')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select('lgas.name as lga_name', DB::raw('COUNT(*) as total_farmers'))
            ->where('farmers.status', 'active')
            ->groupBy('lgas.name')
            ->get()
            ->keyBy('lga_name');

        // Beneficiaries per LGA
        $beneficiariesByLga = DB::table('resource_applications')
            ->join('users', 'resource_applications.user_id', '=', 'users.id')
            ->join('farmers', 'users.id', '=', 'farmers.user_id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select('lgas.name as lga_name', DB::raw('COUNT(DISTINCT farmers.id) as beneficiary_count'))
            ->where('resource_applications.status', 'granted')
            ->groupBy('lgas.name')
            ->get()
            ->keyBy('lga_name');

        // Calculate coverage percentage
        $coverage = $farmersByLga->map(function($item, $lgaName) use ($beneficiariesByLga) {
            $totalFarmers = $item->total_farmers;
            $beneficiaries = $beneficiariesByLga->get($lgaName)->beneficiary_count ?? 0;
            
            return [
                'lga_name' => $lgaName,
                'total_farmers' => $totalFarmers,
                'beneficiaries' => $beneficiaries,
                'coverage_percentage' => $totalFarmers > 0 ? round(($beneficiaries / $totalFarmers) * 100, 2) : 0,
                'gap' => $totalFarmers - $beneficiaries,
            ];
        })->values();

        return response()->json([
            'coverage_data' => $coverage,
            'state_summary' => [
                'total_farmers' => $farmersByLga->sum('total_farmers'),
                'total_beneficiaries' => $beneficiariesByLga->sum('beneficiary_count'),
                'overall_coverage' => $farmersByLga->sum('total_farmers') > 0 
                    ? round(($beneficiariesByLga->sum('beneficiary_count') / $farmersByLga->sum('total_farmers')) * 100, 2) 
                    : 0,
            ],
            'underserved_lgas' => $coverage->where('coverage_percentage', '<', 20)->sortBy('coverage_percentage')->values(),
        ]);
    }
}

