<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Governor Dashboard Controller
 * Main dashboard with quick summary
 */
class GovernorDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'summary' => $this->getSummary(),
            'lga_summary' => $this->getLgaSummary(),
        ];

        return view('governor.dashboard', $data);
    }

    private function getSummary()
    {
        return [
            'total_farmers' => DB::table('farmers')->where('status', 'active')->count(),
            'total_farms' => DB::table('farm_lands')->count(),
            'total_hectares' => DB::table('farm_lands')->sum('total_size_hectares') ?? 0,
            'active_resources' => DB::table('resources')->where('status', 'active')->count(),
            'pending_farmers' => DB::table('farmers')->where('status', 'pending_lga_review')->count(),
            'total_cooperatives' => DB::table('cooperatives')->count(),
            'total_applications' => DB::table('resource_applications')->count(),
        ];
    }

    private function getLgaSummary()
    {
        return DB::table('lgas')
            ->leftJoin('farmers', function($join) {
                $join->on('lgas.id', '=', 'farmers.lga_id')
                     ->where('farmers.status', '=', 'active');
            })
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->select(
                'lgas.name',
                DB::raw('COUNT(DISTINCT farmers.id) as farmers'),
                DB::raw('COUNT(DISTINCT farm_lands.id) as farms'),
                DB::raw('COALESCE(SUM(farm_lands.total_size_hectares), 0) as hectares')
            )
            ->groupBy('lgas.id', 'lgas.name')
            ->orderBy('farmers', 'desc')
            ->limit(10)
            ->get();
    }
}