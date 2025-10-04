<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AdvancedAnalyticsService
{
    /**
     * Generate custom filtered analytics
     * 
     * @param array $filters [
     *   'crop_type' => 'cassava',
     *   'gender' => 'female',
     *   'farm_type' => 'fisheries',
     *   'lga_id' => [1, 2, 3],
     *   'age_range' => ['min' => 18, 'max' => 35],
     *   'educational_level' => ['tertiary', 'secondary'],
     *   'ownership_status' => 'owned',
     *   'farming_method' => 'irrigation',
     *   'cooperative_member' => true,
     *   'marital_status' => 'married',
     *   'primary_occupation' => 'full_time_farmer'
     * ]
     */
    public function getFilteredAnalytics(array $filters): array
    {
        $query = DB::table('farmers')
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->leftJoin('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->whereNull('farmers.deleted_at');

        // Apply filters
        $query = $this->applyFilters($query, $filters);

        // Get base results
        $farmers = $query->select(
            'farmers.*',
            'lgas.name as lga_name',
            'lgas.code as lga_code',
            DB::raw('COUNT(DISTINCT farm_lands.id) as farm_count'),
            DB::raw('SUM(farm_lands.total_size_hectares) as total_land_ha')
        )
        ->groupBy('farmers.id')
        ->get();

        return [
            'summary' => $this->generateSummary($farmers, $filters),
            'demographics' => $this->analyzeDemographics($farmers),
            'geographic_distribution' => $this->analyzeGeography($farmers),
            'production_analysis' => $this->analyzeProduction($filters),
            'detailed_results' => $farmers->toArray(),
        ];
    }

    /**
     * Apply dynamic filters to query
     */
    protected function applyFilters($query, array $filters)
    {
        // Gender filter
        if (isset($filters['gender'])) {
            $query->where('farmers.gender', $filters['gender']);
        }

        // Age range filter
        if (isset($filters['age_range'])) {
            if (isset($filters['age_range']['min'])) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) >= ?', [$filters['age_range']['min']]);
            }
            if (isset($filters['age_range']['max'])) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) <= ?', [$filters['age_range']['max']]);
            }
        }

        // Education level filter
        if (isset($filters['educational_level'])) {
            if (is_array($filters['educational_level'])) {
                $query->whereIn('farmers.educational_level', $filters['educational_level']);
            } else {
                $query->where('farmers.educational_level', $filters['educational_level']);
            }
        }

        // Marital status filter
        if (isset($filters['marital_status'])) {
            $query->where('farmers.marital_status', $filters['marital_status']);
        }

        // Primary occupation filter
        if (isset($filters['primary_occupation'])) {
            $query->where('farmers.primary_occupation', $filters['primary_occupation']);
        }

        // LGA filter (single or multiple)
        if (isset($filters['lga_id'])) {
            if (is_array($filters['lga_id'])) {
                $query->whereIn('farmers.lga_id', $filters['lga_id']);
            } else {
                $query->where('farmers.lga_id', $filters['lga_id']);
            }
        }

        // Cooperative membership filter
        if (isset($filters['cooperative_member'])) {
            if ($filters['cooperative_member']) {
                $query->whereNotNull('farmers.cooperative_id');
            } else {
                $query->whereNull('farmers.cooperative_id');
            }
        }

        // Status filter
        if (isset($filters['status'])) {
            $query->where('farmers.status', $filters['status']);
        }

        // Farm type filter
        if (isset($filters['farm_type'])) {
            $query->where('farm_lands.farm_type', $filters['farm_type']);
        }

        // Ownership status filter
        if (isset($filters['ownership_status'])) {
            $query->where('farm_lands.ownership_status', $filters['ownership_status']);
        }

        // Crop-specific filters
        if (isset($filters['crop_type'])) {
            $query->join('crop_practice_details', 'farm_lands.id', '=', 'crop_practice_details.farm_land_id')
                  ->where('crop_practice_details.crop_type', $filters['crop_type']);
        }

        if (isset($filters['farming_method'])) {
            $query->join('crop_practice_details', 'farm_lands.id', '=', 'crop_practice_details.farm_land_id')
                  ->where('crop_practice_details.farming_method', $filters['farming_method']);
        }

        // Livestock-specific filters
        if (isset($filters['animal_type'])) {
            $query->join('livestock_practice_details', 'farm_lands.id', '=', 'livestock_practice_details.farm_land_id')
                  ->where('livestock_practice_details.animal_type', $filters['animal_type']);
        }

        if (isset($filters['breeding_practice'])) {
            $query->join('livestock_practice_details', 'farm_lands.id', '=', 'livestock_practice_details.farm_land_id')
                  ->where('livestock_practice_details.breeding_practice', $filters['breeding_practice']);
        }

        // Fisheries-specific filters
        if (isset($filters['fishing_type'])) {
            $query->join('fisheries_practice_details', 'farm_lands.id', '=', 'fisheries_practice_details.farm_land_id')
                  ->where('fisheries_practice_details.fishing_type', $filters['fishing_type']);
        }

        if (isset($filters['species_raised'])) {
            $query->join('fisheries_practice_details', 'farm_lands.id', '=', 'fisheries_practice_details.farm_land_id')
                  ->where('fisheries_practice_details.species_raised', $filters['species_raised']);
        }

        return $query;
    }

    /**
     * Generate summary statistics
     */
    protected function generateSummary(Collection $farmers, array $filters): array
    {
        return [
            'total_farmers' => $farmers->count(),
            'total_farms' => $farmers->sum('farm_count'),
            'total_land_hectares' => round($farmers->sum('total_land_ha'), 2),
            'average_land_per_farmer' => $farmers->count() > 0 
                ? round($farmers->sum('total_land_ha') / $farmers->count(), 2) 
                : 0,
            'average_household_size' => round($farmers->avg('household_size'), 2),
            'filters_applied' => $this->formatFiltersApplied($filters),
        ];
    }

    /**
     * Analyze demographics breakdown
     */
    protected function analyzeDemographics(Collection $farmers): array
    {
        return [
            'gender_distribution' => [
                'male' => $farmers->where('gender', 'male')->count(),
                'female' => $farmers->where('gender', 'female')->count(),
                'other' => $farmers->where('gender', 'other')->count(),
            ],
            'age_distribution' => [
                '18-25' => $farmers->filter(fn($f) => $this->calculateAge($f->date_of_birth) >= 18 && $this->calculateAge($f->date_of_birth) <= 25)->count(),
                '26-35' => $farmers->filter(fn($f) => $this->calculateAge($f->date_of_birth) >= 26 && $this->calculateAge($f->date_of_birth) <= 35)->count(),
                '36-45' => $farmers->filter(fn($f) => $this->calculateAge($f->date_of_birth) >= 36 && $this->calculateAge($f->date_of_birth) <= 45)->count(),
                '46-55' => $farmers->filter(fn($f) => $this->calculateAge($f->date_of_birth) >= 46 && $this->calculateAge($f->date_of_birth) <= 55)->count(),
                '56+' => $farmers->filter(fn($f) => $this->calculateAge($f->date_of_birth) >= 56)->count(),
            ],
            'education_distribution' => [
                'none' => $farmers->where('educational_level', 'none')->count(),
                'primary' => $farmers->where('educational_level', 'primary')->count(),
                'secondary' => $farmers->where('educational_level', 'secondary')->count(),
                'tertiary' => $farmers->where('educational_level', 'tertiary')->count(),
                'vocational' => $farmers->where('educational_level', 'vocational')->count(),
            ],
            'marital_status_distribution' => [
                'single' => $farmers->where('marital_status', 'single')->count(),
                'married' => $farmers->where('marital_status', 'married')->count(),
                'divorced' => $farmers->where('marital_status', 'divorced')->count(),
                'widowed' => $farmers->where('marital_status', 'widowed')->count(),
            ],
        ];
    }

    /**
     * Analyze geographic distribution
     */
    protected function analyzeGeography(Collection $farmers): array
    {
        $lgaStats = $farmers->groupBy('lga_name')->map(function($group) {
            return [
                'count' => $group->count(),
                'total_land_ha' => round($group->sum('total_land_ha'), 2),
                'avg_land_ha' => round($group->avg('total_land_ha'), 2),
            ];
        })->toArray();

        return [
            'lga_breakdown' => $lgaStats,
            'total_lgas_covered' => count($lgaStats),
        ];
    }

    /**
     * Analyze production details based on filters
     */
    protected function analyzeProduction(array $filters): array
    {
        $analysis = [];

        // Crop analysis if crop filter is applied
        if (isset($filters['crop_type'])) {
            $analysis['crop_details'] = $this->getCropDetails($filters['crop_type'], $filters);
        }

        // Livestock analysis if animal filter is applied
        if (isset($filters['animal_type'])) {
            $analysis['livestock_details'] = $this->getLivestockDetails($filters['animal_type'], $filters);
        }

        // Fisheries analysis if fishing type is applied
        if (isset($filters['fishing_type']) || isset($filters['farm_type']) && $filters['farm_type'] === 'fisheries') {
            $analysis['fisheries_details'] = $this->getFisheriesDetails($filters);
        }

        return $analysis;
    }

    /**
     * Get detailed crop production information
     */
    protected function getCropDetails(string $cropType, array $filters): array
    {
        $query = DB::table('crop_practice_details')
            ->join('farm_lands', 'crop_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->where('crop_practice_details.crop_type', $cropType)
            ->whereNull('farmers.deleted_at');

        // Apply additional filters
        if (isset($filters['gender'])) {
            $query->where('farmers.gender', $filters['gender']);
        }

        if (isset($filters['lga_id'])) {
            if (is_array($filters['lga_id'])) {
                $query->whereIn('farmers.lga_id', $filters['lga_id']);
            } else {
                $query->where('farmers.lga_id', $filters['lga_id']);
            }
        }

        $results = $query->select(
            DB::raw('COUNT(DISTINCT farmers.id) as farmer_count'),
            DB::raw('COUNT(farm_lands.id) as farm_count'),
            DB::raw('SUM(farm_lands.total_size_hectares) as total_area_ha'),
            DB::raw('SUM(crop_practice_details.expected_yield_kg) as total_expected_yield'),
            DB::raw('AVG(crop_practice_details.expected_yield_kg) as avg_yield_per_farm'),
            'crop_practice_details.farming_method'
        )
        ->groupBy('crop_practice_details.farming_method')
        ->get();

        $totalFarmers = $results->sum('farmer_count');
        $totalArea = $results->sum('total_area_ha');
        $totalYield = $results->sum('total_expected_yield');

        return [
            'crop_type' => ucwords(str_replace('_', ' ', $cropType)),
            'total_farmers' => $totalFarmers,
            'total_farms' => $results->sum('farm_count'),
            'total_area_hectares' => round($totalArea, 2),
            'total_expected_yield_kg' => round($totalYield, 2),
            'yield_per_hectare' => $totalArea > 0 ? round($totalYield / $totalArea, 2) : 0,
            'methods_breakdown' => $results->map(function($item) {
                return [
                    'method' => $item->farming_method,
                    'farmers' => $item->farmer_count,
                    'area_ha' => round($item->total_area_ha, 2),
                    'expected_yield_kg' => round($item->total_expected_yield, 2),
                ];
            })->toArray(),
        ];
    }

    /**
     * Get detailed livestock information
     */
    protected function getLivestockDetails(string $animalType, array $filters): array
    {
        $query = DB::table('livestock_practice_details')
            ->join('farm_lands', 'livestock_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->where('livestock_practice_details.animal_type', $animalType)
            ->whereNull('farmers.deleted_at');

        if (isset($filters['gender'])) {
            $query->where('farmers.gender', $filters['gender']);
        }

        if (isset($filters['lga_id'])) {
            if (is_array($filters['lga_id'])) {
                $query->whereIn('farmers.lga_id', $filters['lga_id']);
            } else {
                $query->where('farmers.lga_id', $filters['lga_id']);
            }
        }

        $results = $query->select(
            DB::raw('COUNT(DISTINCT farmers.id) as farmer_count'),
            DB::raw('SUM(livestock_practice_details.herd_flock_size) as total_animals'),
            DB::raw('AVG(livestock_practice_details.herd_flock_size) as avg_herd_size'),
            'livestock_practice_details.breeding_practice'
        )
        ->groupBy('livestock_practice_details.breeding_practice')
        ->get();

        return [
            'animal_type' => ucwords(str_replace('_', ' ', $animalType)),
            'total_farmers' => $results->sum('farmer_count'),
            'total_animals' => $results->sum('total_animals'),
            'average_herd_size' => round($results->avg('avg_herd_size'), 2),
            'breeding_methods' => $results->map(function($item) {
                return [
                    'method' => $item->breeding_practice,
                    'farmers' => $item->farmer_count,
                    'total_animals' => $item->total_animals,
                ];
            })->toArray(),
        ];
    }

    /**
     * Get detailed fisheries information
     */
    protected function getFisheriesDetails(array $filters): array
    {
        $query = DB::table('fisheries_practice_details')
            ->join('farm_lands', 'fisheries_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->join('farmers', 'farm_lands.farmer_id', '=', 'farmers.id')
            ->whereNull('farmers.deleted_at');

        if (isset($filters['gender'])) {
            $query->where('farmers.gender', $filters['gender']);
        }

        if (isset($filters['lga_id'])) {
            if (is_array($filters['lga_id'])) {
                $query->whereIn('farmers.lga_id', $filters['lga_id']);
            } else {
                $query->where('farmers.lga_id', $filters['lga_id']);
            }
        }

        $results = $query->select(
            DB::raw('COUNT(DISTINCT farmers.id) as farmer_count'),
            DB::raw('SUM(fisheries_practice_details.pond_size_sqm) as total_pond_area'),
            DB::raw('SUM(fisheries_practice_details.expected_harvest_kg) as total_expected_harvest'),
            'fisheries_practice_details.fishing_type'
        )
        ->groupBy('fisheries_practice_details.fishing_type')
        ->get();

        return [
            'total_farmers' => $results->sum('farmer_count'),
            'total_pond_area_sqm' => round($results->sum('total_pond_area'), 2),
            'total_expected_harvest_kg' => round($results->sum('total_expected_harvest'), 2),
            'fishing_types' => $results->map(function($item) {
                return [
                    'type' => $item->fishing_type,
                    'farmers' => $item->farmer_count,
                    'pond_area_sqm' => round($item->total_pond_area, 2),
                    'expected_harvest_kg' => round($item->total_expected_harvest, 2),
                ];
            })->toArray(),
        ];
    }

    /**
     * Calculate age from date of birth
     */
    protected function calculateAge($dateOfBirth): int
    {
        return \Carbon\Carbon::parse($dateOfBirth)->age;
    }

    /**
     * Format applied filters for display
     */
    protected function formatFiltersApplied(array $filters): array
    {
        $formatted = [];
        
        foreach ($filters as $key => $value) {
            $formatted[$key] = is_array($value) ? implode(', ', $value) : $value;
        }
        
        return $formatted;
    }
}