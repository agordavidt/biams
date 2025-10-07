<?php

namespace App\Http\Controllers\Governor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Trend Analysis Controller
 * Tracks temporal changes and forecasts
 */
class TrendAnalysisController extends Controller
{
    public function index()
    {
        return view('governor.trends.index');
    }

    /**
     * Get enrollment trends over time
     */
    public function getEnrollmentTrends(Request $request)
    {
        $validated = $request->validate([
            'period' => 'nullable|in:daily,weekly,monthly,yearly',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'lga_id' => 'nullable|exists:lgas,id',
        ]);

        $period = $validated['period'] ?? 'monthly';
        $startDate = $validated['start_date'] ?? Carbon::now()->subYear();
        $endDate = $validated['end_date'] ?? Carbon::now();

        $dateFormat = match($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%U',
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
        };

        $query = DB::table('farmers')
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period")
            ->selectRaw('COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (isset($validated['lga_id'])) {
            $query->where('lga_id', $validated['lga_id']);
        }

        $trends = $query->groupBy('period')
            ->orderBy('period')
            ->get();

        // Calculate cumulative total
        $cumulative = 0;
        $trends = $trends->map(function($item) use (&$cumulative) {
            $cumulative += $item->count;
            $item->cumulative = $cumulative;
            return $item;
        });

        return response()->json([
            'trends' => $trends,
            'period' => $period,
            'total_new_farmers' => $trends->sum('count'),
        ]);
    }

    /**
     * Production trends - yield changes over time
     */
    public function getProductionTrends(Request $request)
    {
        $validated = $request->validate([
            'crop_type' => 'nullable|string',
            'period' => 'nullable|in:monthly,quarterly,yearly',
        ]);

        $period = $validated['period'] ?? 'monthly';
        
        $dateFormat = match($period) {
            'monthly' => '%Y-%m',
            'quarterly' => "CONCAT(YEAR(created_at), '-Q', QUARTER(created_at))",
            'yearly' => '%Y',
        };

        $query = DB::table('crop_practice_details')
            ->join('farm_lands', 'crop_practice_details.farm_land_id', '=', 'farm_lands.id')
            ->selectRaw($period === 'quarterly' 
                ? "{$dateFormat} as period"
                : "DATE_FORMAT(farm_lands.created_at, '{$dateFormat}') as period")
            ->selectRaw('crop_practice_details.crop_type')
            ->selectRaw('COUNT(DISTINCT farm_lands.id) as farm_count')
            ->selectRaw('SUM(crop_practice_details.expected_yield_kg) as total_expected_yield')
            ->selectRaw('AVG(crop_practice_details.expected_yield_kg) as avg_yield_per_farm');

        if (isset($validated['crop_type'])) {
            $query->where('crop_practice_details.crop_type', $validated['crop_type']);
        }

        $trends = $query->groupBy('period', 'crop_practice_details.crop_type')
            ->orderBy('period')
            ->get();

        return response()->json([
            'production_trends' => $trends,
            'period' => $period,
        ]);
    }

    /**
     * Resource utilization trends
     */
    public function getResourceUtilizationTrends(Request $request)
    {
        $validated = $request->validate([
            'period' => 'nullable|in:monthly,quarterly',
            'resource_id' => 'nullable|exists:resources,id',
        ]);

        $period = $validated['period'] ?? 'monthly';
        
        $dateFormat = $period === 'monthly' ? '%Y-%m' : "CONCAT(YEAR(created_at), '-Q', QUARTER(created_at))";

        $query = DB::table('resource_applications')
            ->join('resources', 'resource_applications.resource_id', '=', 'resources.id')
            ->selectRaw($period === 'quarterly'
                ? "{$dateFormat} as period"
                : "DATE_FORMAT(resource_applications.created_at, '{$dateFormat}') as period")
            ->selectRaw('resources.name as resource_name')
            ->selectRaw('COUNT(*) as application_count')
            ->selectRaw('SUM(CASE WHEN resource_applications.status = "granted" THEN 1 ELSE 0 END) as granted_count')
            ->selectRaw('SUM(CASE WHEN resource_applications.status = "pending" THEN 1 ELSE 0 END) as pending_count');

        if (isset($validated['resource_id'])) {
            $query->where('resource_applications.resource_id', $validated['resource_id']);
        }

        $trends = $query->groupBy('period', 'resources.name')
            ->orderBy('period')
            ->get();

        // Calculate success rates
        $trends = $trends->map(function($item) {
            $item->success_rate = $item->application_count > 0
                ? round(($item->granted_count / $item->application_count) * 100, 2)
                : 0;
            return $item;
        });

        return response()->json([
            'utilization_trends' => $trends,
            'period' => $period,
        ]);
    }

    /**
     * Gender parity trends over time
     */
    public function getGenderParityTrends()
    {
        $trends = DB::table('farmers')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period")
            ->selectRaw('gender')
            ->selectRaw('COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('period', 'gender')
            ->orderBy('period')
            ->get();

        // Transform into more usable format
        $formatted = $trends->groupBy('period')->map(function($items, $period) {
            $female = $items->where('gender', 'Female')->first()->count ?? 0;
            $male = $items->where('gender', 'Male')->first()->count ?? 0;
            $total = $female + $male;

            return [
                'period' => $period,
                'female' => $female,
                'male' => $male,
                'total' => $total,
                'female_percentage' => $total > 0 ? round(($female / $total) * 100, 2) : 0,
                'male_percentage' => $total > 0 ? round(($male / $total) * 100, 2) : 0,
            ];
        })->values();

        return response()->json([
            'gender_parity_trends' => $formatted,
        ]);
    }
}