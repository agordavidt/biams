<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsRepository
{
    protected int $cacheTime = 3600; // 1 hour
    protected bool $useCache = false; // Disable cache by default to avoid tagging issues

    /**
     * Get farmer demographics for LGA(s)
     */
    public function getFarmerDemographics(?int $lgaId = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = DB::table('analytics_farmer_demographics');
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        if ($startDate && $endDate) {
            $query->whereBetween('snapshot_date', [$startDate, $endDate]);
        } else {
            // Get latest snapshot
            $query->whereDate('snapshot_date', DB::table('analytics_farmer_demographics')->max('snapshot_date'));
        }
        
        return $query->get()->toArray();
    }

    /**
     * Get farm production statistics
     */
    public function getFarmProduction(?int $lgaId = null, ?string $date = null): array
    {
        $query = DB::table('analytics_farm_production');
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        $query->whereDate('snapshot_date', $date ?? now()->toDateString());
        
        return $query->get()->toArray();
    }

    /**
     * Get crop production analytics with filtering
     */
    public function getCropProduction(?int $lgaId = null, ?string $cropType = null, ?string $date = null): array
    {
        $query = DB::table('analytics_crop_production');
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        if ($cropType) {
            $query->where('crop_type', $cropType);
        }
        
        $query->whereDate('snapshot_date', $date ?? now()->toDateString());
        
        return $query->orderBy('total_area_ha', 'desc')->get()->toArray();
    }

    /**
     * Get top crops by yield across state or LGA
     */
    public function getTopCropsByYield(?int $lgaId = null, int $limit = 10): array
    {
        $query = DB::table('analytics_crop_production')
            ->whereDate('snapshot_date', now()->toDateString());
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        return $query->orderBy('total_expected_yield_kg', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get livestock production analytics
     */
    public function getLivestockProduction(?int $lgaId = null, ?string $animalType = null): array
    {
        $query = DB::table('analytics_livestock_production')
            ->whereDate('snapshot_date', now()->toDateString());
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        if ($animalType) {
            $query->where('animal_type', $animalType);
        }
        
        return $query->orderBy('total_herd_size', 'desc')->get()->toArray();
    }

    /**
     * Get cooperative engagement metrics
     */
    public function getCooperativeEngagement(?int $lgaId = null): array
    {
        $query = DB::table('analytics_cooperative_engagement')
            ->whereDate('snapshot_date', now()->toDateString());
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        return $query->get()->toArray();
    }

    /**
     * Get enrollment pipeline status
     */
    public function getEnrollmentPipeline(?int $lgaId = null, ?int $enrolledBy = null): array
    {
        $query = DB::table('analytics_enrollment_pipeline')
            ->whereDate('snapshot_date', now()->toDateString());
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        if ($enrolledBy) {
            $query->where('enrolled_by', $enrolledBy);
        }
        
        return $query->get()->toArray();
    }

    /**
     * Get comprehensive dashboard data for a specific role
     */
    public function getDashboardData(string $role, ?int $lgaId = null): array
    {
        $data = [];
        
        switch ($role) {
            case 'Super Admin':
            case 'Governor':
            case 'State Admin':
                // State-wide view
                $data = [
                    'demographics' => $this->getStateDemographicsSummary(),
                    'production' => $this->getStateProductionSummary(),
                    'top_crops' => $this->getTopCropsByYield(null, 5),
                    'top_livestock' => $this->getTopLivestockByHerdSize(null, 5),
                    'cooperatives' => $this->getStateCooperativeSummary(),
                    'enrollment' => $this->getStateEnrollmentSummary(),
                    'lga_breakdown' => $this->getLGAComparison(),
                ];
                break;
                
            case 'LGA Admin':
                // LGA-specific view
                $data = [
                    'demographics' => $this->getFarmerDemographics($lgaId),
                    'production' => $this->getFarmProduction($lgaId),
                    'crops' => $this->getCropProduction($lgaId),
                    'livestock' => $this->getLivestockProduction($lgaId),
                    'cooperatives' => $this->getCooperativeEngagement($lgaId),
                    'enrollment' => $this->getEnrollmentPipeline($lgaId),
                    'agents_performance' => $this->getAgentsPerformance($lgaId),
                ];
                break;
                
            case 'Enrollment Agent':
                // Agent-specific view
                $enrolledBy = auth()->id();
                $data = [
                    'my_enrollments' => $this->getEnrollmentPipeline($lgaId, $enrolledBy),
                    'my_statistics' => $this->getAgentStatistics($enrolledBy),
                ];
                break;
        }
        
        return $data;
    }

    /**
     * Get state-wide demographic summary
     */
    protected function getStateDemographicsSummary(): array
    {
        $result = DB::table('analytics_farmer_demographics')
            ->whereDate('snapshot_date', now()->toDateString())
            ->selectRaw('
                SUM(total_farmers) as total_farmers,
                SUM(male_count) as male_count,
                SUM(female_count) as female_count,
                SUM(age_18_25) as youth_farmers,
                SUM(age_26_35 + age_36_45) as prime_age_farmers,
                SUM(age_46_55 + age_56_plus) as senior_farmers,
                SUM(edu_tertiary + edu_vocational) as educated_farmers,
                AVG(avg_household_size) as state_avg_household_size
            ')
            ->first();
        
        return $result ? (array) $result : [];
    }

    /**
     * Get state-wide production summary
     */
    protected function getStateProductionSummary(): array
    {
        $result = DB::table('analytics_farm_production')
            ->whereDate('snapshot_date', now()->toDateString())
            ->selectRaw('
                SUM(farms_crops + farms_livestock + farms_fisheries + farms_orchards + farms_forestry) as total_farms,
                SUM(total_land_ha) as total_land_ha,
                AVG(avg_farm_size_ha) as state_avg_farm_size,
                SUM(farms_crops) as total_crop_farms,
                SUM(farms_livestock) as total_livestock_farms,
                SUM(total_cropland_ha) as total_cropland_ha
            ')
            ->first();
        
        return $result ? (array) $result : [];
    }

    /**
     * Get state-wide cooperative summary
     */
    protected function getStateCooperativeSummary(): array
    {
        $result = DB::table('analytics_cooperative_engagement')
            ->whereDate('snapshot_date', now()->toDateString())
            ->selectRaw('
                SUM(total_cooperatives) as total_cooperatives,
                SUM(farmers_in_cooperatives) as total_members,
                AVG(cooperative_participation_rate) as avg_participation_rate,
                SUM(total_cooperative_land_ha) as total_cooperative_land
            ')
            ->first();
        
        return $result ? (array) $result : [];
    }

    /**
     * Get state-wide enrollment summary
     */
    protected function getStateEnrollmentSummary(): array
    {
        $result = DB::table('analytics_enrollment_pipeline')
            ->whereDate('snapshot_date', now()->toDateString())
            ->whereNull('enrolled_by') // Get LGA aggregates only
            ->selectRaw('
                SUM(pending_review_count) as pending_review,
                SUM(pending_activation_count) as pending_activation,
                SUM(active_count) as active_farmers,
                SUM(rejected_count) as rejected,
                SUM(new_enrollments_month) as new_this_month,
                AVG(approval_rate) as avg_approval_rate
            ')
            ->first();
        
        return $result ? (array) $result : [];
    }

    /**
     * Get top livestock by herd size
     */
    protected function getTopLivestockByHerdSize(?int $lgaId = null, int $limit = 5): array
    {
        $query = DB::table('analytics_livestock_production')
            ->whereDate('snapshot_date', now()->toDateString());
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        return $query->orderBy('total_herd_size', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get LGA comparison for state-level view
     */
    public function getLGAComparison(): array
    {
        return DB::table('analytics_farmer_demographics as demo')
            ->join('lgas', 'demo.lga_id', '=', 'lgas.id')
            ->leftJoin('analytics_farm_production as prod', function($join) {
                $join->on('demo.lga_id', '=', 'prod.lga_id')
                     ->on('demo.snapshot_date', '=', 'prod.snapshot_date');
            })
            ->whereDate('demo.snapshot_date', now()->toDateString())
            ->select(
                'lgas.name as lga_name',
                'lgas.code as lga_code',
                'demo.total_farmers',
                'prod.total_land_ha',
                'prod.farms_crops',
                'prod.farms_livestock'
            )
            ->orderBy('demo.total_farmers', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get agents performance for LGA Admin
     */
    public function getAgentsPerformance(int $lgaId): array
    {
        return DB::table('analytics_enrollment_pipeline as pipeline')
            ->join('users', 'pipeline.enrolled_by', '=', 'users.id')
            ->where('pipeline.lga_id', $lgaId)
            ->whereNotNull('pipeline.enrolled_by')
            ->whereDate('pipeline.snapshot_date', now()->toDateString())
            ->select(
                'users.name',
                'users.email',
                'pipeline.pending_review_count',
                'pipeline.pending_activation_count',
                'pipeline.active_count',
                'pipeline.rejected_count',
                'pipeline.new_enrollments_month',
                'pipeline.approval_rate'
            )
            ->orderBy('pipeline.active_count', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get individual agent statistics
     */
    protected function getAgentStatistics(int $agentId): array
    {
        $latest = DB::table('analytics_enrollment_pipeline')
            ->where('enrolled_by', $agentId)
            ->whereDate('snapshot_date', now()->toDateString())
            ->first();
        
        if (!$latest) {
            return [
                'total_enrolled' => 0,
                'pending_review' => 0,
                'pending_activation' => 0,
                'active' => 0,
                'rejected' => 0,
                'approval_rate' => 0,
            ];
        }
        
        return [
            'total_enrolled' => $latest->pending_review_count + $latest->pending_activation_count + 
                               $latest->active_count + $latest->rejected_count,
            'pending_review' => $latest->pending_review_count,
            'pending_activation' => $latest->pending_activation_count,
            'active' => $latest->active_count,
            'rejected' => $latest->rejected_count,
            'approval_rate' => $latest->approval_rate,
            'new_this_month' => $latest->new_enrollments_month,
        ];
    }

    /**
     * Get trend data for charts (time series)
     */
    public function getTrendData(string $metric, ?int $lgaId = null, int $days = 30): array
    {
        $startDate = now()->subDays($days)->toDateString();
        $endDate = now()->toDateString();
        
        switch ($metric) {
            case 'farmer_growth':
                return $this->getFarmerGrowthTrend($lgaId, $startDate, $endDate);
            case 'land_expansion':
                return $this->getLandExpansionTrend($lgaId, $startDate, $endDate);
            case 'enrollment_activity':
                return $this->getEnrollmentActivityTrend($lgaId, $startDate, $endDate);
            default:
                return [];
        }
    }

    protected function getFarmerGrowthTrend(?int $lgaId, string $startDate, string $endDate): array
    {
        $query = DB::table('analytics_farmer_demographics')
            ->whereBetween('snapshot_date', [$startDate, $endDate])
            ->select('snapshot_date', DB::raw('SUM(total_farmers) as total'));
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        } else {
            $query->groupBy('snapshot_date');
        }
        
        return $query->orderBy('snapshot_date')->get()->toArray();
    }

    protected function getLandExpansionTrend(?int $lgaId, string $startDate, string $endDate): array
    {
        $query = DB::table('analytics_farm_production')
            ->whereBetween('snapshot_date', [$startDate, $endDate])
            ->select('snapshot_date', DB::raw('SUM(total_land_ha) as total_land'));
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        } else {
            $query->groupBy('snapshot_date');
        }
        
        return $query->orderBy('snapshot_date')->get()->toArray();
    }

    protected function getEnrollmentActivityTrend(?int $lgaId, string $startDate, string $endDate): array
    {
        $query = DB::table('analytics_enrollment_pipeline')
            ->whereBetween('snapshot_date', [$startDate, $endDate])
            ->whereNull('enrolled_by')
            ->select(
                'snapshot_date',
                DB::raw('SUM(new_enrollments_today) as new_enrollments'),
                DB::raw('SUM(approved_today) as approvals'),
                DB::raw('SUM(rejected_today) as rejections')
            );
        
        if ($lgaId) {
            $query->where('lga_id', $lgaId);
        }
        
        return $query->orderBy('snapshot_date')->get()->toArray();
    }

    /**
     * Export data for reports
     */
    public function getExportData(string $reportType, ?int $lgaId = null): array
    {
        return match($reportType) {
            'comprehensive' => $this->getComprehensiveReport($lgaId),
            'demographics' => $this->getFarmerDemographics($lgaId),
            'production' => $this->getProductionReport($lgaId),
            'cooperatives' => $this->getCooperativeReport($lgaId),
            default => []
        };
    }

    protected function getComprehensiveReport(?int $lgaId): array
    {
        return [
            'demographics' => $this->getFarmerDemographics($lgaId),
            'production' => $this->getFarmProduction($lgaId),
            'crops' => $this->getCropProduction($lgaId),
            'livestock' => $this->getLivestockProduction($lgaId),
            'cooperatives' => $this->getCooperativeEngagement($lgaId),
            'enrollment' => $this->getEnrollmentPipeline($lgaId),
        ];
    }

    protected function getProductionReport(?int $lgaId): array
    {
        return [
            'summary' => $this->getFarmProduction($lgaId),
            'crops' => $this->getCropProduction($lgaId),
            'livestock' => $this->getLivestockProduction($lgaId),
        ];
    }

    protected function getCooperativeReport(?int $lgaId): array
    {
        $query = DB::table('cooperatives')
            ->leftJoin('lgas', 'cooperatives.lga_id', '=', 'lgas.id')
            ->select(
                'cooperatives.*',
                'lgas.name as lga_name',
                DB::raw('(SELECT COUNT(*) FROM farmers WHERE farmers.cooperative_id = cooperatives.id) as actual_members')
            );
        
        if ($lgaId) {
            $query->where('cooperatives.lga_id', $lgaId);
        }
        
        return $query->get()->toArray();
    }
}