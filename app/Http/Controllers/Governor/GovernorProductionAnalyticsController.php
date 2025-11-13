<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Governor Production Analytics Controller
 * Farm production and agricultural activity analysis
 */
class GovernorProductionAnalyticsController extends Controller
{
    public function index()
    {
        $data = [
            'farms_overview' => $this->getFarmsOverview(),
            'crop_production' => $this->getCropProduction(),
            'livestock_production' => $this->getLivestockProduction(),
            'other_production' => $this->getOtherProduction(),
        ];

        return view('governor.production', $data);
    }

    public function export()
    {
        $data = [
            'farms_overview' => $this->getFarmsOverview(),
            'crop_production' => $this->getCropProduction(),
            'livestock_production' => $this->getLivestockProduction(),
            'other_production' => $this->getOtherProduction(),
            'generated_at' => now()->format('F d, Y h:i A'),
        ];

        $pdf = PDF::loadView('governor.exports.production', $data);
        return $pdf->download('benue-production-analytics-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getFarmsOverview()
    {
        $totalFarms = DB::table('farm_lands')->count();
        
        return [
            'total_farms' => $totalFarms,
            'total_hectares' => DB::table('farm_lands')->sum('total_size_hectares') ?? 0,
            'avg_farm_size' => DB::table('farm_lands')->avg('total_size_hectares') ?? 0,
            
            // By Type
            'by_type' => DB::table('farm_lands')
                ->select('farm_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_size_hectares) as hectares'))
                ->groupBy('farm_type')
                ->get()
                ->mapWithKeys(function($item) use ($totalFarms) {
                    return [
                        $item->farm_type => [
                            'count' => $item->count,
                            'hectares' => $item->hectares ?? 0,
                            'percentage' => $totalFarms > 0 ? round(($item->count / $totalFarms) * 100, 1) : 0
                        ]
                    ];
                }),
            
            // By Ownership
            'by_ownership' => DB::table('farm_lands')
                ->select('ownership_status', DB::raw('COUNT(*) as count'))
                ->whereNotNull('ownership_status')
                ->groupBy('ownership_status')
                ->get()
                ->mapWithKeys(function($item) use ($totalFarms) {
                    return [
                        $item->ownership_status => [
                            'count' => $item->count,
                            'percentage' => $totalFarms > 0 ? round(($item->count / $totalFarms) * 100, 1) : 0
                        ]
                    ];
                }),
            
            // By LGA
            'by_lga' => DB::table('farm_lands')
                ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
                ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
                ->select(
                    'lgas.name as lga',
                    DB::raw('COUNT(farm_lands.id) as farms'),
                    DB::raw('SUM(farm_lands.total_size_hectares) as hectares')
                )
                ->where('farmers.status', 'active')
                ->groupBy('lgas.id', 'lgas.name')
                ->orderBy('farms', 'desc')
                ->get(),
        ];
    }

    private function getCropProduction()
    {
        return [
            'total_crop_farms' => DB::table('farm_lands')->where('farm_type', 'crops')->count(),
            
            'total_crop_hectares' => DB::table('farm_lands')->where('farm_type', 'crops')->sum('total_size_hectares') ?? 0,
            
            // By Crop Type
            'by_crop_type' => DB::table('crop_practice_details')
                ->select(
                    'crop_type',
                    DB::raw('COUNT(*) as farm_count'),
                    DB::raw('SUM(expected_yield_kg) as total_expected_yield'),
                    DB::raw('AVG(expected_yield_kg) as avg_expected_yield')
                )
                ->whereNotNull('crop_type')
                ->groupBy('crop_type')
                ->orderBy('farm_count', 'desc')
                ->get(),
            
            // By Farming Method
            'by_method' => DB::table('crop_practice_details')
                ->select('farming_method', DB::raw('COUNT(*) as count'))
                ->whereNotNull('farming_method')
                ->groupBy('farming_method')
                ->orderBy('count', 'desc')
                ->get(),
            
            // Top producing LGAs
            'top_lgas' => DB::table('crop_practice_details')
                ->join('farm_lands', 'crop_practice_details.farm_land_id', '=', 'farm_lands.id')
                ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
                ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
                ->select(
                    'lgas.name as lga',
                    'crop_practice_details.crop_type',
                    DB::raw('COUNT(*) as farms'),
                    DB::raw('SUM(crop_practice_details.expected_yield_kg) as total_yield')
                )
                ->where('farmers.status', 'active')
                ->groupBy('lgas.name', 'crop_practice_details.crop_type')
                ->orderBy('total_yield', 'desc')
                ->limit(20)
                ->get(),
        ];
    }

    private function getLivestockProduction()
    {
        return [
            'total_livestock_farms' => DB::table('farm_lands')->where('farm_type', 'livestock')->count(),
            
            // By Animal Type
            'by_animal_type' => DB::table('livestock_practice_details')
                ->select(
                    'animal_type',
                    DB::raw('COUNT(*) as farm_count'),
                    DB::raw('SUM(herd_flock_size) as total_animals')
                )
                ->whereNotNull('animal_type')
                ->groupBy('animal_type')
                ->orderBy('total_animals', 'desc')
                ->get(),
            
            // By Breeding Practice
            'by_breeding_practice' => DB::table('livestock_practice_details')
                ->select('breeding_practice', DB::raw('COUNT(*) as count'))
                ->whereNotNull('breeding_practice')
                ->groupBy('breeding_practice')
                ->orderBy('count', 'desc')
                ->get(),
            
            // By LGA
            'by_lga' => DB::table('livestock_practice_details')
                ->join('farm_lands', 'livestock_practice_details.farm_land_id', '=', 'farm_lands.id')
                ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
                ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
                ->select(
                    'lgas.name as lga',
                    'livestock_practice_details.animal_type',
                    DB::raw('COUNT(*) as farms'),
                    DB::raw('SUM(livestock_practice_details.herd_flock_size) as total_animals')
                )
                ->where('farmers.status', 'active')
                ->groupBy('lgas.name', 'livestock_practice_details.animal_type')
                ->orderBy('total_animals', 'desc')
                ->get(),
        ];
    }

    private function getOtherProduction()
    {
        return [
            // Fisheries
            'fisheries' => [
                'total_farms' => DB::table('farm_lands')->where('farm_type', 'fisheries')->count(),
                
                'by_type' => DB::table('fisheries_practice_details')
                    ->select(
                        'fishing_type',
                        DB::raw('COUNT(*) as farm_count'),
                        DB::raw('SUM(pond_size_sqm) as total_pond_size'),
                        DB::raw('SUM(expected_harvest_kg) as total_expected_harvest')
                    )
                    ->whereNotNull('fishing_type')
                    ->groupBy('fishing_type')
                    ->get(),
                
                'species' => DB::table('fisheries_practice_details')
                    ->select('species_raised', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('species_raised')
                    ->groupBy('species_raised')
                    ->orderBy('count', 'desc')
                    ->get(),
            ],
            
            // Orchards
            'orchards' => [
                'total_farms' => DB::table('farm_lands')->where('farm_type', 'orchards')->count(),
                'total_hectares' => DB::table('farm_lands')->where('farm_type', 'orchards')->sum('total_size_hectares') ?? 0,
                
                'by_tree_type' => DB::table('orchard_practice_details')
                    ->select(
                        'tree_type',
                        DB::raw('COUNT(*) as farm_count'),
                        DB::raw('SUM(number_of_trees) as total_trees')
                    )
                    ->whereNotNull('tree_type')
                    ->groupBy('tree_type')
                    ->orderBy('total_trees', 'desc')
                    ->get(),
                
                'maturity_stages' => DB::table('orchard_practice_details')
                    ->select('maturity_stage', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('maturity_stage')
                    ->groupBy('maturity_stage')
                    ->get(),
            ],
        ];
    }
}