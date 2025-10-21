<?php

namespace App\Http\Controllers\Commissioner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * LGA Comparison Controller
 * Provides side-by-side comparison of LGAs
 */
class LgaComparisonController extends Controller
{
    public function index()
    {
        return view('commissioner.lga-comparison.index');
    }

    /**
     * Get comprehensive LGA performance ranking
     */
    public function getPerformanceRanking(Request $request)
    {
        $metric = $request->input('metric', 'total_farmers'); // total_farmers, total_hectares, avg_farm_size, etc.

        $rankings = DB::table('lgas')
            ->leftJoin('farmers', 'lgas.id', '=', 'farmers.lga_id')
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->select(
                'lgas.name as lga_name',
                'lgas.code as lga_code',
                DB::raw('COUNT(DISTINCT farmers.id) as total_farmers'),
                DB::raw('COALESCE(SUM(farm_lands.total_size_hectares), 0) as total_hectares'),
                DB::raw('COALESCE(AVG(farm_lands.total_size_hectares), 0) as avg_farm_size'),
                DB::raw('COUNT(DISTINCT CASE WHEN farmers.status = "active" THEN farmers.id END) as active_farmers'),
                DB::raw('COUNT(DISTINCT CASE WHEN farmers.status = "pending_lga_review" THEN farmers.id END) as pending_farmers')
            )
            ->groupBy('lgas.id', 'lgas.name', 'lgas.code')
            ->orderByDesc($metric)
            ->get();

        // Add rankings
        $rankings = $rankings->map(function($item, $index) {
            $item->rank = $index + 1;
            return $item;
        });

        return response()->json([
            'rankings' => $rankings,
            'metric_used' => $metric,
        ]);
    }

    /**
     * Detailed capacity analysis per LGA
     */
    public function getCapacityAnalysis(Request $request)
    {
        $lgaId = $request->input('lga_id');

        $query = DB::table('lgas')
            ->leftJoin('farmers', 'lgas.id', '=', 'farmers.lga_id')
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->select(
                'lgas.name as lga_name',
                // Crop farms
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "crops" THEN farm_lands.id END) as crop_farms'),
                DB::raw('SUM(CASE WHEN farm_lands.farm_type = "crops" THEN farm_lands.total_size_hectares ELSE 0 END) as crop_hectares'),
                // Livestock farms
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "livestock" THEN farm_lands.id END) as livestock_farms'),
                // Fisheries
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "fisheries" THEN farm_lands.id END) as fishery_farms'),
                // Orchards
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "orchards" THEN farm_lands.id END) as orchard_farms'),
                DB::raw('SUM(CASE WHEN farm_lands.farm_type = "orchards" THEN farm_lands.total_size_hectares ELSE 0 END) as orchard_hectares')
            )
            ->groupBy('lgas.id', 'lgas.name');

        if ($lgaId) {
            $query->where('lgas.id', $lgaId);
        }

        $capacityData = $query->get();

        // Get cooperative membership per LGA
        $cooperativeData = DB::table('lgas')
            ->leftJoin('cooperatives', 'lgas.id', '=', 'cooperatives.lga_id')
            ->select(
                'lgas.name as lga_name',
                DB::raw('COUNT(cooperatives.id) as cooperative_count'),
                DB::raw('SUM(cooperatives.total_member_count) as total_coop_members')
            )
            ->when($lgaId, function($q) use ($lgaId) {
                return $q->where('lgas.id', $lgaId);
            })
            ->groupBy('lgas.name')
            ->get()
            ->keyBy('lga_name');

        // Merge data
        $analysis = $capacityData->map(function($item) use ($cooperativeData) {
            $coopInfo = $cooperativeData->get($item->lga_name);
            $item->cooperative_count = $coopInfo->cooperative_count ?? 0;
            $item->coop_members = $coopInfo->total_coop_members ?? 0;
            return $item;
        });

        return response()->json([
            'capacity_analysis' => $analysis,
        ]);
    }

    /**
     * Side-by-side comparison of specific LGAs
     */
    public function compareLgas(Request $request)
    {
        $validated = $request->validate([
            'lga_ids' => 'required|array|min:2|max:5',
            'lga_ids.*' => 'exists:lgas,id',
        ]);

        $lgaIds = $validated['lga_ids'];

        // Basic statistics
        $comparison = DB::table('lgas')
            ->leftJoin('farmers', 'lgas.id', '=', 'farmers.lga_id')
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->select(
                'lgas.name as lga_name',
                'lgas.code as lga_code',
                DB::raw('COUNT(DISTINCT farmers.id) as total_farmers'),
                DB::raw('COUNT(DISTINCT CASE WHEN farmers.gender = "Female" THEN farmers.id END) as female_farmers'),
                DB::raw('COUNT(DISTINCT CASE WHEN farmers.gender = "Male" THEN farmers.id END) as male_farmers'),
                DB::raw('COALESCE(SUM(farm_lands.total_size_hectares), 0) as total_hectares'),
                DB::raw('COALESCE(AVG(farm_lands.total_size_hectares), 0) as avg_farm_size'),
                DB::raw('COUNT(DISTINCT farm_lands.id) as total_farms')
            )
            ->whereIn('lgas.id', $lgaIds)
            ->where('farmers.status', 'active')
            ->groupBy('lgas.id', 'lgas.name', 'lgas.code')
            ->get();

        // Farm type distribution
        $farmTypeDistribution = DB::table('farm_lands')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga_name',
                'farm_lands.farm_type',
                DB::raw('COUNT(*) as count')
            )
            ->whereIn('lgas.id', $lgaIds)
            ->where('farmers.status', 'active')
            ->groupBy('lgas.name', 'farm_lands.farm_type')
            ->get()
            ->groupBy('lga_name');

        // Top crops per LGA
        $topCrops = DB::table('crop_practice_details')
            ->join('farm_lands', 'crop_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga_name',
                'crop_practice_details.crop_type',
                DB::raw('COUNT(*) as farm_count'),
                DB::raw('SUM(crop_practice_details.expected_yield_kg) as total_expected_yield')
            )
            ->whereIn('lgas.id', $lgaIds)
            ->where('farmers.status', 'active')
            ->groupBy('lgas.name', 'crop_practice_details.crop_type')
            ->get()
            ->groupBy('lga_name')
            ->map(function($crops) {
                return $crops->sortByDesc('farm_count')->take(5)->values();
            });

        // Resource application success rates
        $resourceStats = DB::table('resource_applications')
            ->join('users', 'resource_applications.user_id', '=', 'users.id')
            ->join('farmers', 'users.id', '=', 'farmers.user_id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga_name',
                DB::raw('COUNT(*) as total_applications'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as granted'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "pending" THEN 1 ELSE 0 END) as pending')
            )
            ->whereIn('lgas.id', $lgaIds)
            ->groupBy('lgas.name')
            ->get()
            ->map(function($item) {
                $item->success_rate = $item->total_applications > 0 
                    ? round(($item->granted / $item->total_applications) * 100, 2) 
                    : 0;
                return $item;
            });

        return response()->json([
            'basic_comparison' => $comparison,
            'farm_type_distribution' => $farmTypeDistribution,
            'top_crops' => $topCrops,
            'resource_statistics' => $resourceStats,
        ]);
    }

    /**
     * Geographic distribution and density analysis
     */
    public function getGeographicAnalysis()
    {
        $analysis = DB::table('lgas')
            ->leftJoin('farmers', 'lgas.id', '=', 'farmers.lga_id')
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->select(
                'lgas.name as lga_name',
                'lgas.code',
                DB::raw('COUNT(DISTINCT farmers.id) as farmer_count'),
                DB::raw('COALESCE(SUM(farm_lands.total_size_hectares), 0) as total_hectares'),
                DB::raw('COUNT(DISTINCT farm_lands.id) as farm_count')
            )
            ->where('farmers.status', 'active')
            ->groupBy('lgas.id', 'lgas.name', 'lgas.code')
            ->orderByDesc('farmer_count')
            ->get();

        // Calculate percentages and density metrics
        $totalFarmers = $analysis->sum('farmer_count');
        $totalHectares = $analysis->sum('total_hectares');

        $analysis = $analysis->map(function($item) use ($totalFarmers, $totalHectares) {
            $item->farmer_percentage = $totalFarmers > 0 
                ? round(($item->farmer_count / $totalFarmers) * 100, 2) 
                : 0;
            $item->hectare_percentage = $totalHectares > 0 
                ? round(($item->total_hectares / $totalHectares) * 100, 2) 
                : 0;
            $item->farms_per_farmer = $item->farmer_count > 0 
                ? round($item->farm_count / $item->farmer_count, 2) 
                : 0;
            return $item;
        });

        return response()->json([
            'geographic_distribution' => $analysis,
            'state_totals' => [
                'total_farmers' => $totalFarmers,
                'total_hectares' => $totalHectares,
            ],
        ]);
    }
}