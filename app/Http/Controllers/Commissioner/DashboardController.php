<?php

namespace App\Http\Controllers\Commissioner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Main Commissioner Dashboard Controller
 * Presents high-level state-wide KPIs and trends
 */
class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'stateKpis' => $this->getStateKpis(),
            'recentTrends' => $this->getRecentTrends(),
            'criticalAlerts' => $this->getCriticalAlerts(),
            'lgaSnapshot' => $this->getLgaSnapshot(),
        ];

        return view('commissioner.dashboard', $data);
    }

    private function getStateKpis(): array
    {
        return [
            'total_farmers' => DB::table('farmers')
                ->where('status', 'active')
                ->count(),
            
            'total_hectares' => DB::table('farm_lands')
                ->sum('total_size_hectares'),
            
            'total_cooperatives' => DB::table('cooperatives')->count(),
            
            'active_resources' => DB::table('resources')
                ->where(function($q) {
                    $today = Carbon::today();
                    $q->where(function($q2) use ($today) {
                        $q2->whereNull('start_date')
                           ->orWhere('start_date', '<=', $today);
                    })->where(function($q2) use ($today) {
                        $q2->whereNull('end_date')
                           ->orWhere('end_date', '>=', $today);
                    });
                })
                ->count(),
            
            'pending_applications' => DB::table('resource_applications')
                ->where('status', 'pending')
                ->count(),
        ];
    }

    private function getRecentTrends(): array
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $sixtyDaysAgo = Carbon::now()->subDays(60);

        // New farmers - last 30 days vs previous 30 days
        $newFarmersRecent = DB::table('farmers')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();
        
        $newFarmersPrevious = DB::table('farmers')
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        return [
            'new_farmers' => [
                'current' => $newFarmersRecent,
                'previous' => $newFarmersPrevious,
                'change_percentage' => $this->calculatePercentageChange($newFarmersPrevious, $newFarmersRecent),
            ],
            'new_applications' => [
                'current' => DB::table('resource_applications')
                    ->where('created_at', '>=', $thirtyDaysAgo)
                    ->count(),
            ],
        ];
    }

    private function getCriticalAlerts(): array
    {
        return [
            'expiring_resources' => DB::table('resources')
                ->whereNotNull('end_date')
                ->where('end_date', '<=', Carbon::now()->addDays(7))
                ->where('end_date', '>=', Carbon::today())
                ->count(),
            
            'pending_approvals' => DB::table('farmers')
                ->where('status', 'pending_lga_review')
                ->count(),
        ];
    }

    private function getLgaSnapshot(): array
    {
        return DB::table('farmers')
            ->select('lga_id', DB::raw('COUNT(*) as farmer_count'))
            ->where('status', 'active')
            ->groupBy('lga_id')
            ->orderByDesc('farmer_count')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $lga = DB::table('lgas')->find($item->lga_id);
                return [
                    'lga_name' => $lga->name ?? 'Unknown',
                    'farmer_count' => $item->farmer_count,
                ];
            })
            ->toArray();
    }

    private function calculatePercentageChange($previous, $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
}