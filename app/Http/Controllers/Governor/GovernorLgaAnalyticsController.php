<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Governor LGA Analytics Controller
 * Comparative analysis across Local Government Areas
 */
class GovernorLgaAnalyticsController extends Controller
{
    public function index()
    {
        $data = [
            'lga_comparison' => $this->getLgaComparison(),
            'performance_ranking' => $this->getPerformanceRanking(),
            'resource_distribution' => $this->getResourceDistribution(),
        ];

        return view('governor.lgas', $data);
    }

    public function export()
    {
        $data = [
            'lga_comparison' => $this->getLgaComparison(),
            'performance_ranking' => $this->getPerformanceRanking(),
            'resource_distribution' => $this->getResourceDistribution(),
            'generated_at' => now()->format('F d, Y h:i A'),
        ];

        $pdf = PDF::loadView('governor.exports.lgas', $data);
        return $pdf->download('benue-lga-analytics-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getLgaComparison()
    {
        return DB::table('lgas')
            ->leftJoin('farmers', function($join) {
                $join->on('lgas.id', '=', 'farmers.lga_id')
                     ->where('farmers.status', '=', 'active');
            })
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->leftJoin('cooperatives', 'lgas.id', '=', 'cooperatives.lga_id')
            ->select(
                'lgas.name',
                'lgas.code',
                
                // Farmers
                DB::raw('COUNT(DISTINCT farmers.id) as total_farmers'),
                DB::raw('COUNT(DISTINCT CASE WHEN farmers.gender = "Female" THEN farmers.id END) as female_farmers'),
                DB::raw('COUNT(DISTINCT CASE WHEN farmers.gender = "Male" THEN farmers.id END) as male_farmers'),
                DB::raw('COUNT(DISTINCT CASE WHEN TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) BETWEEN 18 AND 35 THEN farmers.id END) as youth_farmers'),
                
                // Farms
                DB::raw('COUNT(DISTINCT farm_lands.id) as total_farms'),
                DB::raw('COALESCE(SUM(farm_lands.total_size_hectares), 0) as total_hectares'),
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "crops" THEN farm_lands.id END) as crop_farms'),
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "livestock" THEN farm_lands.id END) as livestock_farms'),
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "fisheries" THEN farm_lands.id END) as fishery_farms'),
                DB::raw('COUNT(DISTINCT CASE WHEN farm_lands.farm_type = "orchards" THEN farm_lands.id END) as orchard_farms'),
                
                // Cooperatives
                DB::raw('COUNT(DISTINCT cooperatives.id) as cooperatives'),
                
                // Average farm size
                DB::raw('COALESCE(AVG(farm_lands.total_size_hectares), 0) as avg_farm_size')
            )
            ->groupBy('lgas.id', 'lgas.name', 'lgas.code')
            ->orderBy('total_farmers', 'desc')
            ->get();
    }

    private function getPerformanceRanking()
    {
        // Get base statistics
        $stats = DB::table('lgas')
            ->leftJoin('farmers', function($join) {
                $join->on('lgas.id', '=', 'farmers.lga_id')
                     ->where('farmers.status', '=', 'active');
            })
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->select(
                'lgas.name',
                DB::raw('COUNT(DISTINCT farmers.id) as farmers'),
                DB::raw('COALESCE(SUM(farm_lands.total_size_hectares), 0) as hectares'),
                DB::raw('COUNT(DISTINCT farm_lands.id) as farms')
            )
            ->groupBy('lgas.id', 'lgas.name')
            ->get();

        // Get resource application success rates
        $resourceStats = DB::table('lgas')
            ->leftJoin('farmers', function($join) {
                $join->on('lgas.id', '=', 'farmers.lga_id')
                     ->where('farmers.status', '=', 'active');
            })
            ->leftJoin('resource_applications', function($join) {
                $join->on('farmers.user_id', '=', 'resource_applications.user_id');
            })
            ->select(
                'lgas.name',
                DB::raw('COUNT(resource_applications.id) as total_applications'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as approved_applications')
            )
            ->groupBy('lgas.id', 'lgas.name')
            ->get()
            ->keyBy('name');

        // Combine and rank
        $rankings = $stats->map(function($item) use ($resourceStats) {
            $resourceStat = $resourceStats->get($item->name);
            $totalApps = $resourceStat->total_applications ?? 0;
            
            return [
                'lga' => $item->name,
                'farmers' => $item->farmers,
                'hectares' => round($item->hectares, 2),
                'farms' => $item->farms,
                'applications' => $totalApps,
                'approved' => $resourceStat->approved_applications ?? 0,
                'success_rate' => $totalApps > 0 
                    ? round(($resourceStat->approved_applications / $totalApps) * 100, 1) 
                    : 0,
            ];
        })->sortByDesc('farmers')->values();

        return $rankings;
    }

    private function getResourceDistribution()
    {
        // Applications by LGA
        $applications = DB::table('resource_applications')
            ->join('users', 'resource_applications.user_id', '=', 'users.id')
            ->join('farmers', 'users.id', '=', 'farmers.user_id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga',
                DB::raw('COUNT(resource_applications.id) as total'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "declined" THEN 1 ELSE 0 END) as declined')
            )
            ->where('farmers.status', 'active')
            ->groupBy('lgas.name')
            ->orderBy('total', 'desc')
            ->get();

        // Top resources by LGA
        $topResources = DB::table('resource_applications')
            ->join('resources', 'resource_applications.resource_id', '=', 'resources.id')
            ->join('users', 'resource_applications.user_id', '=', 'users.id')
            ->join('farmers', 'users.id', '=', 'farmers.user_id')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga',
                'resources.name as resource',
                DB::raw('COUNT(resource_applications.id) as applications')
            )
            ->where('farmers.status', 'active')
            ->groupBy('lgas.name', 'resources.name')
            ->orderBy('lgas.name')
            ->orderBy('applications', 'desc')
            ->get()
            ->groupBy('lga')
            ->map(function($items) {
                return $items->take(5);
            });

        return [
            'applications_by_lga' => $applications,
            'top_resources_by_lga' => $topResources,
            
            // Coverage analysis
            'coverage' => DB::table('lgas')
                ->leftJoin('farmers', function($join) {
                    $join->on('lgas.id', '=', 'farmers.lga_id')
                         ->where('farmers.status', '=', 'active');
                })
                ->leftJoin('resource_applications', function($join) {
                    $join->on('farmers.user_id', '=', 'resource_applications.user_id')
                         ->where('resource_applications.status', '=', 'granted');
                })
                ->select(
                    'lgas.name',
                    DB::raw('COUNT(DISTINCT farmers.id) as total_farmers'),
                    DB::raw('COUNT(DISTINCT CASE WHEN resource_applications.id IS NOT NULL THEN farmers.id END) as beneficiaries')
                )
                ->groupBy('lgas.id', 'lgas.name')
                ->get()
                ->map(function($item) {
                    $item->coverage_rate = $item->total_farmers > 0 
                        ? round(($item->beneficiaries / $item->total_farmers) * 100, 1) 
                        : 0;
                    return $item;
                })
                ->sortBy('coverage_rate')
                ->values(),
        ];
    }
}