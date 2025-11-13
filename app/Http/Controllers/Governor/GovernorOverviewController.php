<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Governor Overview Controller
 * Provides high-level system summary
 */

class GovernorOverviewController extends Controller
{
    public function index()
    {
        $data = [
            'summary' => $this->getSummaryStatistics(),
            'lga_summary' => $this->getLgaSummary(),
            'recent_trends' => $this->getRecentTrends(),
        ];

        return view('governor.overview', $data);
    }

    public function export()
    {
        $data = [
            'summary' => $this->getSummaryStatistics(),
            'lga_summary' => $this->getLgaSummary(),
            'recent_trends' => $this->getRecentTrends(),
            'generated_at' => now()->format('F d, Y h:i A'),
        ];

        $pdf = PDF::loadView('governor.exports.overview', $data);
        return $pdf->download('benue-agriculture-overview-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getSummaryStatistics()
    {
        return [
            // Farmers
            'total_farmers' => DB::table('farmers')->where('status', 'active')->count(),
            'pending_farmers' => DB::table('farmers')->where('status', 'pending_lga_review')->count(),
            'female_farmers' => DB::table('farmers')->where('status', 'active')->where('gender', 'Female')->count(),
            'male_farmers' => DB::table('farmers')->where('status', 'active')->where('gender', 'Male')->count(),
            
            // Youth (18-35)
            'youth_farmers' => DB::table('farmers')
                ->where('status', 'active')
                ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 35')
                ->count(),
            
            // Farms
            'total_farms' => DB::table('farm_lands')->count(),
            'total_hectares' => DB::table('farm_lands')->sum('total_size_hectares') ?? 0,
            'crop_farms' => DB::table('farm_lands')->where('farm_type', 'crops')->count(),
            'livestock_farms' => DB::table('farm_lands')->where('farm_type', 'livestock')->count(),
            'fishery_farms' => DB::table('farm_lands')->where('farm_type', 'fisheries')->count(),
            'orchard_farms' => DB::table('farm_lands')->where('farm_type', 'orchards')->count(),
            
            // Cooperatives
            'total_cooperatives' => DB::table('cooperatives')->count(),
            'coop_members' => DB::table('cooperative_farmer')
                ->where('membership_status', 'active')
                ->distinct('farmer_id')
                ->count(),
            
            // Resources & Vendors
            'active_resources' => DB::table('resources')->where('status', 'active')->count(),
            'total_applications' => DB::table('resource_applications')->count(),
            'approved_applications' => DB::table('resource_applications')->where('status', 'granted')->count(),
            'active_vendors' => DB::table('vendors')->where('is_active', true)->count(),
            
            // LGAs
            'total_lgas' => DB::table('lgas')->count(),
        ];
    }

    private function getLgaSummary()
    {
        return DB::table('lgas')
            ->leftJoin('farmers', 'lgas.id', '=', 'farmers.lga_id')
            ->leftJoin('farm_lands', 'farmers.id', '=', 'farm_lands.farmer_id')
            ->select(
                'lgas.name',
                DB::raw('COUNT(DISTINCT CASE WHEN farmers.status = "active" THEN farmers.id END) as farmers'),
                DB::raw('COUNT(DISTINCT farm_lands.id) as farms'),
                DB::raw('COALESCE(SUM(farm_lands.total_size_hectares), 0) as hectares')
            )
            ->groupBy('lgas.id', 'lgas.name')
            ->orderBy('farmers', 'desc')
            ->get();
    }

    private function getRecentTrends()
    {
        // Last 6 months farmer enrollment
        $enrollments = DB::table('farmers')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Resource application trends
        $applications = DB::table('resource_applications')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(CASE WHEN status = "granted" THEN 1 ELSE 0 END) as granted')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'enrollments' => $enrollments,
            'applications' => $applications,
        ];
    }
}