<?php

namespace App\Services;

use App\Models\Farmer;
use App\Models\FarmLand;
use App\Models\Cooperative;
use App\Models\LGA;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Generate snapshot for all LGAs or a specific LGA
     */
    public function generateDailySnapshot(?int $lgaId = null): void
    {
        $date = now()->toDateString();
        $lgas = $lgaId ? LGA::where('id', $lgaId)->get() : LGA::all();

        foreach ($lgas as $lga) {
            $this->generateFarmerDemographics($lga->id, $date);
            $this->generateFarmProduction($lga->id, $date);
            $this->generateCropProduction($lga->id, $date);
            $this->generateLivestockProduction($lga->id, $date);
            $this->generateCooperativeEngagement($lga->id, $date);
            $this->generateEnrollmentPipeline($lga->id, $date);
        }

        // Clear analytics cache
        Cache::flush();
    }

    /**
     * Generate farmer demographics snapshot
     */
    protected function generateFarmerDemographics(int $lgaId, string $date): void
    {
        $farmers = Farmer::where('lga_id', $lgaId)
            ->whereNotNull('date_of_birth')
            ->get();

        $data = [
            'lga_id' => $lgaId,
            'snapshot_date' => $date,
            'total_farmers' => $farmers->count(),
            
            // Gender
            'male_count' => $farmers->where('gender', 'male')->count(),
            'female_count' => $farmers->where('gender', 'female')->count(),
            'other_gender_count' => $farmers->where('gender', 'other')->count(),
            
            // Age groups
            'age_18_25' => $farmers->filter(fn($f) => $f->age >= 18 && $f->age <= 25)->count(),
            'age_26_35' => $farmers->filter(fn($f) => $f->age >= 26 && $f->age <= 35)->count(),
            'age_36_45' => $farmers->filter(fn($f) => $f->age >= 36 && $f->age <= 45)->count(),
            'age_46_55' => $farmers->filter(fn($f) => $f->age >= 46 && $f->age <= 55)->count(),
            'age_56_plus' => $farmers->filter(fn($f) => $f->age >= 56)->count(),
            
            // Education
            'edu_none' => $farmers->where('educational_level', 'none')->count(),
            'edu_primary' => $farmers->where('educational_level', 'primary')->count(),
            'edu_secondary' => $farmers->where('educational_level', 'secondary')->count(),
            'edu_tertiary' => $farmers->where('educational_level', 'tertiary')->count(),
            'edu_vocational' => $farmers->where('educational_level', 'vocational')->count(),
            
            // Marital status
            'marital_single' => $farmers->where('marital_status', 'single')->count(),
            'marital_married' => $farmers->where('marital_status', 'married')->count(),
            'marital_divorced' => $farmers->where('marital_status', 'divorced')->count(),
            'marital_widowed' => $farmers->where('marital_status', 'widowed')->count(),
            
            // Occupation
            'occupation_full_time' => $farmers->where('primary_occupation', 'full_time_farmer')->count(),
            'occupation_part_time' => $farmers->where('primary_occupation', 'part_time_farmer')->count(),
            'occupation_other' => $farmers->whereNotIn('primary_occupation', ['full_time_farmer', 'part_time_farmer'])->count(),
            
            // Household
            'avg_household_size' => round($farmers->avg('household_size'), 2),
        ];

        DB::table('analytics_farmer_demographics')->updateOrInsert(
            ['lga_id' => $lgaId, 'snapshot_date' => $date],
            array_merge($data, ['updated_at' => now()])
        );
    }

    /**
     * Generate farm production snapshot
     */
    protected function generateFarmProduction(int $lgaId, string $date): void
    {
        $farmLands = FarmLand::whereHas('farmer', function($q) use ($lgaId) {
            $q->where('lga_id', $lgaId);
        })->get();

        $data = [
            'lga_id' => $lgaId,
            'snapshot_date' => $date,
            
            // Farm types
            'farms_crops' => $farmLands->where('farm_type', 'crops')->count(),
            'farms_livestock' => $farmLands->where('farm_type', 'livestock')->count(),
            'farms_fisheries' => $farmLands->where('farm_type', 'fisheries')->count(),
            'farms_orchards' => $farmLands->where('farm_type', 'orchards')->count(),
            'farms_forestry' => $farmLands->where('farm_type', 'forestry')->count(),
            
            // Ownership
            'ownership_owned' => $farmLands->where('ownership_status', 'owned')->count(),
            'ownership_leased' => $farmLands->where('ownership_status', 'leased')->count(),
            'ownership_shared' => $farmLands->where('ownership_status', 'shared')->count(),
            'ownership_communal' => $farmLands->where('ownership_status', 'communal')->count(),
            
            // Land areas
            'total_cropland_ha' => $farmLands->where('farm_type', 'crops')->sum('total_size_hectares'),
            'total_livestock_land_ha' => $farmLands->where('farm_type', 'livestock')->sum('total_size_hectares'),
            'total_fisheries_area_ha' => $farmLands->where('farm_type', 'fisheries')->sum('total_size_hectares'),
            'total_orchard_land_ha' => $farmLands->where('farm_type', 'orchards')->sum('total_size_hectares'),
            'total_forestry_land_ha' => $farmLands->where('farm_type', 'forestry')->sum('total_size_hectares'),
            'total_land_ha' => $farmLands->sum('total_size_hectares'),
            
            // Averages
            'avg_farm_size_ha' => $farmLands->count() > 0 ? round($farmLands->avg('total_size_hectares'), 4) : 0,
            'avg_cropland_size_ha' => $farmLands->where('farm_type', 'crops')->count() > 0 
                ? round($farmLands->where('farm_type', 'crops')->avg('total_size_hectares'), 4) : 0,
        ];

        DB::table('analytics_farm_production')->updateOrInsert(
            ['lga_id' => $lgaId, 'snapshot_date' => $date],
            array_merge($data, ['updated_at' => now()])
        );
    }

    /**
     * Generate crop-specific production analytics
     */
    protected function generateCropProduction(int $lgaId, string $date): void
    {
        $cropData = DB::table('farm_lands')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->join('crop_practice_details', 'farm_lands.id', '=', 'crop_practice_details.farm_land_id')
            ->where('farmers.lga_id', $lgaId)
            ->where('farm_lands.farm_type', 'crops')
            ->whereNull('farm_lands.deleted_at')
            ->whereNull('farmers.deleted_at')
            ->select(
                'crop_practice_details.crop_type',
                DB::raw('COUNT(DISTINCT farmers.id) as farmer_count'),
                DB::raw('COUNT(farm_lands.id) as farm_count'),
                DB::raw('SUM(farm_lands.total_size_hectares) as total_area_ha'),
                DB::raw('SUM(crop_practice_details.expected_yield_kg) as total_expected_yield_kg'),
                DB::raw('SUM(CASE WHEN farming_method = "irrigation" THEN 1 ELSE 0 END) as method_irrigation'),
                DB::raw('SUM(CASE WHEN farming_method = "rain_fed" THEN 1 ELSE 0 END) as method_rain_fed'),
                DB::raw('SUM(CASE WHEN farming_method = "organic" THEN 1 ELSE 0 END) as method_organic'),
                DB::raw('SUM(CASE WHEN farming_method = "mixed" THEN 1 ELSE 0 END) as method_mixed')
            )
            ->groupBy('crop_practice_details.crop_type')
            ->get();

        foreach ($cropData as $crop) {
            $avgYieldPerHa = $crop->total_area_ha > 0 
                ? round($crop->total_expected_yield_kg / $crop->total_area_ha, 2) 
                : 0;

            DB::table('analytics_crop_production')->updateOrInsert(
                ['lga_id' => $lgaId, 'crop_type' => $crop->crop_type, 'snapshot_date' => $date],
                [
                    'farmer_count' => $crop->farmer_count,
                    'farm_count' => $crop->farm_count,
                    'total_area_ha' => $crop->total_area_ha,
                    'total_expected_yield_kg' => $crop->total_expected_yield_kg,
                    'avg_yield_per_ha' => $avgYieldPerHa,
                    'method_irrigation' => $crop->method_irrigation,
                    'method_rain_fed' => $crop->method_rain_fed,
                    'method_organic' => $crop->method_organic,
                    'method_mixed' => $crop->method_mixed,
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Generate livestock production analytics
     */
    protected function generateLivestockProduction(int $lgaId, string $date): void
    {
        $livestockData = DB::table('farm_lands')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->join('livestock_practice_details', 'farm_lands.id', '=', 'livestock_practice_details.farm_land_id')
            ->where('farmers.lga_id', $lgaId)
            ->where('farm_lands.farm_type', 'livestock')
            ->whereNull('farm_lands.deleted_at')
            ->whereNull('farmers.deleted_at')
            ->select(
                'livestock_practice_details.animal_type',
                DB::raw('COUNT(DISTINCT farmers.id) as farmer_count'),
                DB::raw('COUNT(farm_lands.id) as farm_count'),
                DB::raw('SUM(livestock_practice_details.herd_flock_size) as total_herd_size'),
                DB::raw('SUM(CASE WHEN breeding_practice = "open_grazing" THEN 1 ELSE 0 END) as practice_open_grazing'),
                DB::raw('SUM(CASE WHEN breeding_practice = "ranching" THEN 1 ELSE 0 END) as practice_ranching'),
                DB::raw('SUM(CASE WHEN breeding_practice = "intensive" THEN 1 ELSE 0 END) as practice_intensive'),
                DB::raw('SUM(CASE WHEN breeding_practice = "semi_intensive" THEN 1 ELSE 0 END) as practice_semi_intensive')
            )
            ->groupBy('livestock_practice_details.animal_type')
            ->get();

        foreach ($livestockData as $livestock) {
            $avgHerdSize = $livestock->farm_count > 0 
                ? round($livestock->total_herd_size / $livestock->farm_count, 2) 
                : 0;

            DB::table('analytics_livestock_production')->updateOrInsert(
                ['lga_id' => $lgaId, 'animal_type' => $livestock->animal_type, 'snapshot_date' => $date],
                [
                    'farmer_count' => $livestock->farmer_count,
                    'farm_count' => $livestock->farm_count,
                    'total_herd_size' => $livestock->total_herd_size,
                    'avg_herd_size' => $avgHerdSize,
                    'practice_open_grazing' => $livestock->practice_open_grazing,
                    'practice_ranching' => $livestock->practice_ranching,
                    'practice_intensive' => $livestock->practice_intensive,
                    'practice_semi_intensive' => $livestock->practice_semi_intensive,
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Generate cooperative engagement analytics
     */
    protected function generateCooperativeEngagement(int $lgaId, string $date): void
    {
        $totalFarmers = Farmer::where('lga_id', $lgaId)->count();
        $farmersInCoops = Farmer::where('lga_id', $lgaId)
            ->whereNotNull('cooperative_id')
            ->count();
        
        $cooperatives = Cooperative::where('lga_id', $lgaId)->get();

        $data = [
            'lga_id' => $lgaId,
            'snapshot_date' => $date,
            'total_cooperatives' => $cooperatives->count(),
            'farmers_in_cooperatives' => $farmersInCoops,
            'farmers_not_in_cooperatives' => $totalFarmers - $farmersInCoops,
            'cooperative_participation_rate' => $totalFarmers > 0 
                ? round(($farmersInCoops / $totalFarmers) * 100, 2) 
                : 0,
            'avg_cooperative_size' => $cooperatives->count() > 0 
                ? round($cooperatives->avg('total_member_count'), 2) 
                : 0,
            'total_cooperative_land_ha' => $cooperatives->sum('total_land_size'),
        ];

        DB::table('analytics_cooperative_engagement')->updateOrInsert(
            ['lga_id' => $lgaId, 'snapshot_date' => $date],
            array_merge($data, ['updated_at' => now()])
        );
    }

    /**
     * Generate enrollment pipeline analytics
     */
    protected function generateEnrollmentPipeline(int $lgaId, string $date): void
    {
        $today = Carbon::parse($date);
        $weekAgo = $today->copy()->subWeek();
        $monthAgo = $today->copy()->subMonth();

        // Get all enrollment agents for this LGA
        $agents = DB::table('users')
            ->where('administrative_type', LGA::class)
            ->where('administrative_id', $lgaId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'Enrollment Agent');
            })
            ->pluck('id');

        foreach ($agents as $agentId) {
            $farmers = Farmer::where('lga_id', $lgaId)
                ->where('enrolled_by', $agentId);

            $allFarmers = $farmers->get();

            $data = [
                'lga_id' => $lgaId,
                'enrolled_by' => $agentId,
                'snapshot_date' => $date,
                
                // Status counts
                'pending_review_count' => $allFarmers->where('status', 'pending_lga_review')->count(),
                'pending_activation_count' => $allFarmers->where('status', 'pending_activation')->count(),
                'active_count' => $allFarmers->where('status', 'active')->count(),
                'rejected_count' => $allFarmers->where('status', 'rejected')->count(),
                'suspended_count' => $allFarmers->where('status', 'suspended')->count(),
                
                // New enrollments
                'new_enrollments_today' => (clone $farmers)
                    ->whereDate('created_at', $today)->count(),
                'new_enrollments_week' => (clone $farmers)
                    ->where('created_at', '>=', $weekAgo)->count(),
                'new_enrollments_month' => (clone $farmers)
                    ->where('created_at', '>=', $monthAgo)->count(),
                
                // Approvals
                'approved_today' => (clone $farmers)
                    ->whereDate('approved_at', $today)
                    ->where('status', 'pending_activation')
                    ->count(),
                'rejected_today' => (clone $farmers)
                    ->whereDate('updated_at', $today)
                    ->where('status', 'rejected')
                    ->count(),
            ];

            $totalProcessed = $data['approved_today'] + $data['rejected_today'];
            $data['approval_rate'] = $totalProcessed > 0 
                ? round(($data['approved_today'] / $totalProcessed) * 100, 2) 
                : 0;

            DB::table('analytics_enrollment_pipeline')->updateOrInsert(
                ['lga_id' => $lgaId, 'enrolled_by' => $agentId, 'snapshot_date' => $date],
                array_merge($data, ['updated_at' => now()])
            );
        }

        // Also create aggregate for entire LGA (enrolled_by = null)
        $allLgaFarmers = Farmer::where('lga_id', $lgaId)->get();
        
        $aggregateData = [
            'lga_id' => $lgaId,
            'enrolled_by' => null,
            'snapshot_date' => $date,
            'pending_review_count' => $allLgaFarmers->where('status', 'pending_lga_review')->count(),
            'pending_activation_count' => $allLgaFarmers->where('status', 'pending_activation')->count(),
            'active_count' => $allLgaFarmers->where('status', 'active')->count(),
            'rejected_count' => $allLgaFarmers->where('status', 'rejected')->count(),
            'suspended_count' => $allLgaFarmers->where('status', 'suspended')->count(),
            'new_enrollments_today' => Farmer::where('lga_id', $lgaId)->whereDate('created_at', $today)->count(),
            'new_enrollments_week' => Farmer::where('lga_id', $lgaId)->where('created_at', '>=', $weekAgo)->count(),
            'new_enrollments_month' => Farmer::where('lga_id', $lgaId)->where('created_at', '>=', $monthAgo)->count(),
            'approved_today' => Farmer::where('lga_id', $lgaId)->whereDate('approved_at', $today)->count(),
            'rejected_today' => Farmer::where('lga_id', $lgaId)->whereDate('updated_at', $today)->where('status', 'rejected')->count(),
        ];

        $totalProcessed = $aggregateData['approved_today'] + $aggregateData['rejected_today'];
        $aggregateData['approval_rate'] = $totalProcessed > 0 
            ? round(($aggregateData['approved_today'] / $totalProcessed) * 100, 2) 
            : 0;

        DB::table('analytics_enrollment_pipeline')->updateOrInsert(
            ['lga_id' => $lgaId, 'enrolled_by' => null, 'snapshot_date' => $date],
            array_merge($aggregateData, ['updated_at' => now()])
        );
    }
}