<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Governor Farmer Analytics Controller
 * Detailed farmer demographics and statistics
 */
class GovernorFarmerAnalyticsController extends Controller
{
    public function index()
    {
        $data = [
            'demographics' => $this->getDemographics(),
            'by_lga' => $this->getFarmersByLga(),
            'education' => $this->getEducationBreakdown(),
            'cooperatives' => $this->getCooperativeStats(),
        ];

        return view('governor.farmers', $data);
    }

    public function export()
    {
        $data = [
            'demographics' => $this->getDemographics(),
            'by_lga' => $this->getFarmersByLga(),
            'education' => $this->getEducationBreakdown(),
            'cooperatives' => $this->getCooperativeStats(),
            'generated_at' => now()->format('F d, Y h:i A'),
        ];

        $pdf = PDF::loadView('governor.exports.farmers', $data);
        return $pdf->download('benue-farmer-analytics-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getDemographics()
    {
        $total = DB::table('farmers')->where('status', 'active')->count();

        return [
            'total' => $total,
            
            // Gender
            'gender' => DB::table('farmers')
                ->select('gender', DB::raw('COUNT(*) as count'))
                ->where('status', 'active')
                ->groupBy('gender')
                ->get()
                ->mapWithKeys(function($item) use ($total) {
                    return [
                        $item->gender => [
                            'count' => $item->count,
                            'percentage' => $total > 0 ? round(($item->count / $total) * 100, 1) : 0
                        ]
                    ];
                }),
            
            // Age Groups
            'age_groups' => DB::table('farmers')
                ->selectRaw("
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 35 THEN 'Youth (18-35)'
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 50 THEN 'Adult (36-50)'
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 51 AND 64 THEN 'Senior (51-64)'
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 65 THEN 'Elderly (65+)'
                        ELSE 'Unknown'
                    END as age_group,
                    COUNT(*) as count
                ")
                ->where('status', 'active')
                ->whereNotNull('date_of_birth')
                ->groupBy('age_group')
                ->get()
                ->mapWithKeys(function($item) use ($total) {
                    return [
                        $item->age_group => [
                            'count' => $item->count,
                            'percentage' => $total > 0 ? round(($item->count / $total) * 100, 1) : 0
                        ]
                    ];
                }),
            
            // Marital Status
            'marital_status' => DB::table('farmers')
                ->select('marital_status', DB::raw('COUNT(*) as count'))
                ->where('status', 'active')
                ->whereNotNull('marital_status')
                ->groupBy('marital_status')
                ->get()
                ->mapWithKeys(function($item) use ($total) {
                    return [
                        $item->marital_status => [
                            'count' => $item->count,
                            'percentage' => $total > 0 ? round(($item->count / $total) * 100, 1) : 0
                        ]
                    ];
                }),
            
            // Average Household Size
            'avg_household_size' => DB::table('farmers')
                ->where('status', 'active')
                ->whereNotNull('household_size')
                ->avg('household_size') ?? 0,
        ];
    }

    private function getFarmersByLga()
    {
        return DB::table('farmers')
            ->join('lgas', 'farmers.lga_id', '=', 'lgas.id')
            ->select(
                'lgas.name as lga',
                DB::raw('COUNT(CASE WHEN farmers.status = "active" THEN 1 END) as active'),
                DB::raw('COUNT(CASE WHEN farmers.status = "pending_lga_review" THEN 1 END) as pending'),
                DB::raw('COUNT(CASE WHEN farmers.gender = "Female" AND farmers.status = "active" THEN 1 END) as female'),
                DB::raw('COUNT(CASE WHEN farmers.gender = "Male" AND farmers.status = "active" THEN 1 END) as male'),
                DB::raw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, farmers.date_of_birth, CURDATE()) BETWEEN 18 AND 35 AND farmers.status = "active" THEN 1 END) as youth')
            )
            ->groupBy('lgas.id', 'lgas.name')
            ->orderBy('active', 'desc')
            ->get();
    }

    private function getEducationBreakdown()
    {
        return DB::table('farmers')
            ->select('educational_level', DB::raw('COUNT(*) as count'))
            ->where('status', 'active')
            ->whereNotNull('educational_level')
            ->groupBy('educational_level')
            ->orderBy('count', 'desc')
            ->get();
    }

    private function getCooperativeStats()
    {
        return [
            'total_cooperatives' => DB::table('cooperatives')->count(),
            
            'farmers_in_cooperatives' => DB::table('cooperative_farmer')
                ->where('membership_status', 'active')
                ->distinct('farmer_id')
                ->count(),
            
            'by_lga' => DB::table('cooperatives')
                ->join('lgas', 'cooperatives.lga_id', '=', 'lgas.id')
                ->select(
                    'lgas.name as lga',
                    DB::raw('COUNT(cooperatives.id) as cooperative_count'),
                    DB::raw('SUM(cooperatives.total_member_count) as total_members')
                )
                ->groupBy('lgas.id', 'lgas.name')
                ->orderBy('cooperative_count', 'desc')
                ->get(),
            
            'top_cooperatives' => DB::table('cooperatives')
                ->join('lgas', 'cooperatives.lga_id', '=', 'lgas.id')
                ->select(
                    'cooperatives.name',
                    'lgas.name as lga',
                    'cooperatives.total_member_count',
                    'cooperatives.total_land_size'
                )
                ->orderBy('total_member_count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
}