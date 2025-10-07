<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Policy Insights Controller
 * Handles complex multi-dimensional queries for policy decisions
 */
class PolicyInsightController extends Controller
{
    /**
     * Main insights dashboard
     */
    public function index()
    {
        return view('governor.policy-insights.index');
    }

    /**
     * Demographic Analysis - e.g., "Female farmers in cassava production"
     */
    public function getDemographicAnalysis(Request $request)
    {
        $filters = $request->validate([
            'gender' => 'nullable|in:Male,Female',
            'age_group' => 'nullable|in:Youth,Adult,Senior',
            'crop_type' => 'nullable|string',
            'farm_type' => 'nullable|in:crops,livestock,fisheries,orchards',
            'lga_id' => 'nullable|exists:lgas,id',
            'educational_level' => 'nullable|string',
        ]);

        $query = DB::table('farmers')
            ->join('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->where('farmers.status', 'active');

        // Apply demographic filters
        if (isset($filters['gender'])) {
            $query->where('farmers.gender', $filters['gender']);
        }

        if (isset($filters['age_group'])) {
            $query->where(function($q) use ($filters) {
                switch ($filters['age_group']) {
                    case 'Youth':
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) BETWEEN 18 AND 35');
                        break;
                    case 'Adult':
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) BETWEEN 36 AND 59');
                        break;
                    case 'Senior':
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) >= 60');
                        break;
                }
            });
        }

        if (isset($filters['lga_id'])) {
            $query->where('farmers.lga_id', $filters['lga_id']);
        }

        if (isset($filters['educational_level'])) {
            $query->where('farmers.educational_level', $filters['educational_level']);
        }

        if (isset($filters['farm_type'])) {
            $query->where('farm_lands.farm_type', $filters['farm_type']);
        }

        // Apply crop-specific filter if provided
        if (isset($filters['crop_type'])) {
            $query->join('crop_practice_details', 'farm_lands.id', '=', 'crop_practice_details.farm_land_id')
                  ->where('crop_practice_details.crop_type', $filters['crop_type']);
        }

        $results = [
            'total_count' => $query->distinct('farmers.id')->count('farmers.id'),
            'total_hectares' => $query->sum('farm_lands.total_size_hectares'),
            'average_farm_size' => $query->avg('farm_lands.total_size_hectares'),
            'lga_distribution' => $this->getLgaDistribution($query),
            'age_distribution' => $this->getAgeDistribution($filters),
            'gender_breakdown' => $this->getGenderBreakdown($filters),
        ];

        return response()->json($results);
    }

    /**
     * Youth Engagement Analysis
     */
    public function getYouthEngagement(Request $request)
    {
        $filters = $request->validate([
            'farm_type' => 'nullable|in:crops,livestock,fisheries,orchards',
            'lga_id' => 'nullable|exists:lgas,id',
        ]);

        $query = DB::table('farmers')
            ->join('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->whereRaw('TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) BETWEEN 18 AND 35')
            ->where('farmers.status', 'active');

        if (isset($filters['farm_type'])) {
            $query->where('farm_lands.farm_type', $filters['farm_type']);
        }

        if (isset($filters['lga_id'])) {
            $query->where('farmers.lga_id', $filters['lga_id']);
        }

        return response()->json([
            'youth_farmers_count' => $query->distinct('farmers.id')->count('farmers.id'),
            'total_hectares' => $query->sum('farm_lands.total_size_hectares'),
            'average_farm_size' => $query->avg('farm_lands.total_size_hectares'),
            'farm_type_distribution' => $this->getFarmTypeDistribution($query),
            'lga_distribution' => $this->getLgaDistribution($query),
            'gender_split' => DB::table('farmers')
                ->selectRaw('gender, COUNT(*) as count')
                ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 35')
                ->where('status', 'active')
                ->groupBy('gender')
                ->get(),
        ]);
    }

    /**
     * Yield Projections by LGA and Crop
     */
    public function getYieldProjections(Request $request)
    {
        $filters = $request->validate([
            'crop_type' => 'nullable|string',
            'lga_id' => 'nullable|exists:lgas,id',
        ]);

        $query = DB::table('crop_practice_details')
            ->join('farm_lands', 'crop_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga_name',
                'crop_practice_details.crop_type',
                DB::raw('SUM(crop_practice_details.expected_yield_kg) as total_expected_yield'),
                DB::raw('AVG(crop_practice_details.expected_yield_kg) as average_yield_per_farm'),
                DB::raw('COUNT(DISTINCT farm_lands.id) as number_of_farms')
            )
            ->where('farmers.status', 'active');

        if (isset($filters['crop_type'])) {
            $query->where('crop_practice_details.crop_type', $filters['crop_type']);
        }

        if (isset($filters['lga_id'])) {
            $query->where('farmers.lga_id', $filters['lga_id']);
        }

        $projections = $query->groupBy('lgas.name', 'crop_practice_details.crop_type')
            ->orderBy('total_expected_yield', 'desc')
            ->get();

        return response()->json([
            'projections' => $projections,
            'state_total' => $projections->sum('total_expected_yield'),
            'top_producing_lgas' => $projections->groupBy('lga_name')
                ->map(function($items, $lga) {
                    return [
                        'lga' => $lga,
                        'total_yield' => $items->sum('total_expected_yield'),
                        'crops' => $items->count(),
                    ];
                })
                ->sortByDesc('total_yield')
                ->take(10)
                ->values(),
        ]);
    }

    /**
     * Production Pattern Analysis - Understanding what's being grown where
     */
    public function getProductionPatterns(Request $request)
    {
        $cropPatterns = DB::table('crop_practice_details')
            ->join('farm_lands', 'crop_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'crop_practice_details.crop_type',
                DB::raw('COUNT(DISTINCT farm_lands.id) as farm_count'),
                DB::raw('SUM(farm_lands.total_size_hectares) as total_hectares'),
                'lgas.name as dominant_lga'
            )
            ->where('farmers.status', 'active')
            ->groupBy('crop_practice_details.crop_type')
            ->orderByDesc('farm_count')
            ->get();

        $livestockPatterns = DB::table('livestock_practice_details')
            ->join('farm_lands', 'livestock_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->select(
                'livestock_practice_details.animal_type',
                DB::raw('COUNT(DISTINCT farm_lands.id) as farm_count'),
                DB::raw('SUM(livestock_practice_details.herd_flock_size) as total_animals')
            )
            ->where('farmers.status', 'active')
            ->groupBy('livestock_practice_details.animal_type')
            ->orderByDesc('farm_count')
            ->get();

        return response()->json([
            'crop_patterns' => $cropPatterns,
            'livestock_patterns' => $livestockPatterns,
            'diversification_index' => $this->calculateDiversificationIndex(),
        ]);
    }

    // Helper methods
    private function getLgaDistribution($query)
    {
        return (clone $query)
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select('lgas.name', DB::raw('COUNT(DISTINCT farmers.id) as count'))
            ->groupBy('lgas.name')
            ->orderByDesc('count')
            ->get();
    }

    private function getAgeDistribution($filters)
    {
        $query = DB::table('farmers')
            ->selectRaw("
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 35 THEN 'Youth'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 59 THEN 'Adult'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60 THEN 'Senior'
                END as age_group,
                COUNT(*) as count
            ")
            ->where('status', 'active')
            ->whereNotNull('date_of_birth');

        if (isset($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        return $query->groupBy('age_group')->get();
    }

    private function getGenderBreakdown($filters)
    {
        $query = DB::table('farmers')
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->where('status', 'active');

        if (isset($filters['lga_id'])) {
            $query->where('lga_id', $filters['lga_id']);
        }

        return $query->groupBy('gender')->get();
    }

    private function getFarmTypeDistribution($query)
    {
        return (clone $query)
            ->select('farm_lands.farm_type', DB::raw('COUNT(DISTINCT farm_lands.id) as count'))
            ->groupBy('farm_lands.farm_type')
            ->get();
    }

    private function calculateDiversificationIndex(): float
    {
        // Simple diversification metric: number of different crop types / total farms
        $uniqueCrops = DB::table('crop_practice_details')->distinct('crop_type')->count('crop_type');
        $totalCropFarms = DB::table('farm_lands')->where('farm_type', 'crops')->count();
        
        return $totalCropFarms > 0 ? round($uniqueCrops / $totalCropFarms, 3) : 0;
    }
}