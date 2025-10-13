<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmLand;
use App\Models\Farmer;
use App\Models\LGA;
use App\Models\CropPracticeDetails;
use App\Models\LivestockPracticeDetails;
use App\Models\FisheriesPracticeDetails;
use App\Models\OrchardPracticeDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmPracticeController extends Controller
{
    public function index(Request $request)
    {
        // Get comprehensive statistics
        $stats = $this->getComprehensiveStats();
        
     
        $farmTypeDistribution = $this->getFarmTypeDistribution(); 
        $lgaDistribution = $this->getLGADistribution($request);
        $practiceAnalytics = $this->getPracticeAnalytics();
        // Get recent farms for overview
        $recentFarms = FarmLand::with([
            'farmer.lga',
            'cropPracticeDetails',
            'livestockPracticeDetails',
            'fisheriesPracticeDetails',
            'orchardPracticeDetails'
        ])
            ->latest()
            ->limit(10)
            ->get();       
       
        
        $lgas = LGA::orderBy('name')->get();
        
        return view('admin.farm-practices.index', compact(
            'stats',
            'farmTypeDistribution',
            'lgaDistribution',
            'practiceAnalytics',
            'recentFarms',
            'lgas'
        ));
    }

    public function crops(Request $request)
    {
        $query = CropPracticeDetails::with(['farmLand.farmer.lga'])
            ->select('crop_practice_details.*');

        // Apply filters
        if ($request->filled('crop_type')) {
            $query->where('crop_type', $request->crop_type);
        }

        if ($request->filled('farming_method')) {
            $query->where('farming_method', $request->farming_method);
        }

        if ($request->filled('lga_id')) {
            $query->whereHas('farmLand.farmer', function($q) use ($request) {
                $q->where('lga_id', $request->lga_id);
            });
        }

        $crops = $query->paginate(20)->withQueryString();

        // Crop-specific statistics
        $cropStats = [
            'totalCropFarms' => CropPracticeDetails::count(),
            'totalExpectedYield' => CropPracticeDetails::sum('expected_yield_kg'),
            'avgYieldPerFarm' => CropPracticeDetails::avg('expected_yield_kg'),
            'cropTypes' => CropPracticeDetails::select('crop_type', DB::raw('count(*) as count'))
                ->groupBy('crop_type')
                ->orderByDesc('count')
                ->get(),
            'farmingMethods' => CropPracticeDetails::select('farming_method', DB::raw('count(*) as count'))
                ->groupBy('farming_method')
                ->get(),
            'topVarieties' => CropPracticeDetails::select('crop_type', 'variety', DB::raw('count(*) as count'))
                ->groupBy('crop_type', 'variety')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
        ];

        $lgas = LGA::orderBy('name')->get();

        return view('admin.farm-practices.crops', compact('crops', 'cropStats', 'lgas'));
    }

    public function livestock(Request $request)
    {
        $query = LivestockPracticeDetails::with(['farmLand.farmer.lga']);

        // Apply filters
        if ($request->filled('animal_type')) {
            $query->where('animal_type', $request->animal_type);
        }

        if ($request->filled('lga_id')) {
            $query->whereHas('farmLand.farmer', function($q) use ($request) {
                $q->where('lga_id', $request->lga_id);
            });
        }

        $livestock = $query->paginate(20)->withQueryString();

        // Livestock-specific statistics
        $livestockStats = [
            'totalLivestockFarms' => LivestockPracticeDetails::count(),
            'totalAnimals' => LivestockPracticeDetails::sum('herd_flock_size'),
            'avgHerdSize' => LivestockPracticeDetails::avg('herd_flock_size'),
            'animalTypes' => LivestockPracticeDetails::select('animal_type', 
                DB::raw('count(*) as farm_count'),
                DB::raw('sum(herd_flock_size) as total_animals'))
                ->groupBy('animal_type')
                ->orderByDesc('total_animals')
                ->get(),
            'breedingPractices' => LivestockPracticeDetails::select('breeding_practice', DB::raw('count(*) as count'))
                ->groupBy('breeding_practice')
                ->get(),
        ];

        $lgas = LGA::orderBy('name')->get();

        return view('admin.farm-practices.livestock', compact('livestock', 'livestockStats', 'lgas'));
    }

    public function fisheries(Request $request)
    {
        $query = FisheriesPracticeDetails::with(['farmLand.farmer.lga']);

        // Apply filters
        if ($request->filled('fishing_type')) {
            $query->where('fishing_type', $request->fishing_type);
        }

        if ($request->filled('lga_id')) {
            $query->whereHas('farmLand.farmer', function($q) use ($request) {
                $q->where('lga_id', $request->lga_id);
            });
        }

        $fisheries = $query->paginate(20)->withQueryString();

        // Fisheries-specific statistics
        $fisheriesStats = [
            'totalFishFarms' => FisheriesPracticeDetails::count(),
            'totalPondSize' => FisheriesPracticeDetails::sum('pond_size_sqm'),
            'totalExpectedHarvest' => FisheriesPracticeDetails::sum('expected_harvest_kg'),
            'avgPondSize' => FisheriesPracticeDetails::avg('pond_size_sqm'),
            'fishingTypes' => FisheriesPracticeDetails::select('fishing_type', DB::raw('count(*) as count'))
                ->groupBy('fishing_type')
                ->get(),
            'speciesRaised' => FisheriesPracticeDetails::select('species_raised', 
                DB::raw('count(*) as farm_count'),
                DB::raw('sum(expected_harvest_kg) as total_expected'))
                ->groupBy('species_raised')
                ->orderByDesc('total_expected')
                ->get(),
        ];

        $lgas = LGA::orderBy('name')->get();

        return view('admin.farm-practices.fisheries', compact('fisheries', 'fisheriesStats', 'lgas'));
    }

    public function orchards(Request $request)
    {
        $query = OrchardPracticeDetails::with(['farmLand.farmer.lga']);

        // Apply filters
        if ($request->filled('tree_type')) {
            $query->where('tree_type', $request->tree_type);
        }

        if ($request->filled('maturity_stage')) {
            $query->where('maturity_stage', $request->maturity_stage);
        }

        if ($request->filled('lga_id')) {
            $query->whereHas('farmLand.farmer', function($q) use ($request) {
                $q->where('lga_id', $request->lga_id);
            });
        }

        $orchards = $query->paginate(20)->withQueryString();

        // Orchard-specific statistics
        $orchardStats = [
            'totalOrchards' => OrchardPracticeDetails::count(),
            'totalTrees' => OrchardPracticeDetails::sum('number_of_trees'),
            'avgTreesPerOrchard' => OrchardPracticeDetails::avg('number_of_trees'),
            'treeTypes' => OrchardPracticeDetails::select('tree_type', 
                DB::raw('count(*) as farm_count'),
                DB::raw('sum(number_of_trees) as total_trees'))
                ->groupBy('tree_type')
                ->orderByDesc('total_trees')
                ->get(),
            'maturityDistribution' => OrchardPracticeDetails::select('maturity_stage', DB::raw('count(*) as count'))
                ->groupBy('maturity_stage')
                ->get(),
        ];

        $lgas = LGA::orderBy('name')->get();

        return view('admin.farm-practices.orchards', compact('orchards', 'orchardStats', 'lgas'));
    }

   private function getComprehensiveStats()
    {
        
        $farmersWithFarms = Farmer::whereHas('farmLands')
            ->withCount('farmLands')
            ->get();
        
        $farmsPerFarmer = $farmersWithFarms->avg('farm_lands_count') ?? 0;

        return [
            'totalFarms' => FarmLand::count(),
            'totalFarmers' => Farmer::whereHas('farmLands')->count(),
            'totalLandSize' => FarmLand::sum('total_size_hectares'),
            'avgFarmSize' => FarmLand::avg('total_size_hectares'),
            'farmsPerFarmer' => $farmsPerFarmer,
            'cropFarms' => FarmLand::where('farm_type', 'crops')->count(),
            'livestockFarms' => FarmLand::where('farm_type', 'livestock')->count(),
            'fisheriesFarms' => FarmLand::where('farm_type', 'fisheries')->count(),
            'orchardFarms' => FarmLand::where('farm_type', 'orchards')->count(),
        ];
    }

    private function getFarmTypeDistribution()
    {
        return FarmLand::select('farm_type', 
            DB::raw('count(*) as count'),
            DB::raw('sum(total_size_hectares) as total_hectares'),
            DB::raw('avg(total_size_hectares) as avg_hectares'))
            ->groupBy('farm_type')
            ->get();
    }

    private function getLGADistribution($request)
    {
        $query = LGA::withCount([
            'farmers as total_farmers',
            'farmers as farmers_with_farms' => function($q) {
                $q->whereHas('farmLands');
            }
        ])
        ->with(['farmers' => function($query) {
            $query->with(['farmLands' => function($q) {
                $q->select('id', 'farmer_id', 'farm_type', 'total_size_hectares');
            }]);
        }]);

        if ($request->filled('lga_id')) {
            $query->where('id', $request->lga_id);
        }

        return $query->get()->map(function($lga) {
            $farms = $lga->farmers->flatMap->farmLands;
            
            return [
                'lga' => $lga,
                'total_farms' => $farms->count(),
                'total_land_size' => $farms->sum('total_size_hectares'),
                'crop_farms' => $farms->where('farm_type', 'crops')->count(),
                'livestock_farms' => $farms->where('farm_type', 'livestock')->count(),
                'fisheries_farms' => $farms->where('farm_type', 'fisheries')->count(),
                'orchard_farms' => $farms->where('farm_type', 'orchards')->count(),
            ];
        })->filter(fn($item) => $item['total_farms'] > 0);
    }

    private function getPracticeAnalytics()
    {
        return [
            'crops' => [
                'topCrops' => CropPracticeDetails::select('crop_type', 
                    DB::raw('count(*) as farm_count'),
                    DB::raw('sum(expected_yield_kg) as total_expected_yield'))
                    ->groupBy('crop_type')
                    ->orderByDesc('total_expected_yield')
                    ->limit(5)
                    ->get(),
            ],
            'livestock' => [
                'topAnimals' => LivestockPracticeDetails::select('animal_type',
                    DB::raw('count(*) as farm_count'),
                    DB::raw('sum(herd_flock_size) as total_animals'))
                    ->groupBy('animal_type')
                    ->orderByDesc('total_animals')
                    ->limit(5)
                    ->get(),
            ],
            'fisheries' => [
                'topSpecies' => FisheriesPracticeDetails::select('species_raised',
                    DB::raw('count(*) as farm_count'),
                    DB::raw('sum(expected_harvest_kg) as total_expected'))
                    ->groupBy('species_raised')
                    ->orderByDesc('total_expected')
                    ->limit(5)
                    ->get(),
            ],
            'orchards' => [
                'topTrees' => OrchardPracticeDetails::select('tree_type',
                    DB::raw('count(*) as farm_count'),
                    DB::raw('sum(number_of_trees) as total_trees'))
                    ->groupBy('tree_type')
                    ->orderByDesc('total_trees')
                    ->limit(5)
                    ->get(),
            ],
        ];
    }
}