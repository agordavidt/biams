<?php



namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Services\AdvancedAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvancedAnalyticsController extends Controller
{
    protected AdvancedAnalyticsService $service;

    public function __construct(AdvancedAnalyticsService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the advanced analytics filter page
     */
    public function index()
    {
        $this->authorize('view_analytics');
        
        // Get available filter options
        $filterOptions = $this->getFilterOptions();
        
        return view('analytics.advanced', compact('filterOptions'));
    }

    /**
     * Generate custom filtered report
     */
    public function generate(Request $request)
    {
        $this->authorize('view_analytics');
        
        // Build filters from request
        $filters = $this->buildFiltersFromRequest($request);
        
        // Generate analytics
        $results = $this->service->getFilteredAnalytics($filters);
        
        if ($request->expectsJson()) {
            return response()->json($results);
        }
        
        // Return view with results
        $filterOptions = $this->getFilterOptions();
        return view('analytics.advanced-results', compact('results', 'filters', 'filterOptions'));
    }

    /**
     * Export filtered results
     */
    public function export(Request $request)
    {
        $this->authorize('export_analytics');
        
        $filters = $this->buildFiltersFromRequest($request);
        $results = $this->service->getFilteredAnalytics($filters);
        
        $format = $request->input('format', 'csv');
        $timestamp = now()->format('Y-m-d_His');
        $filename = "custom_analytics_{$timestamp}";
        
        if ($format === 'json') {
            return response()->json($results)
                ->header('Content-Disposition', "attachment; filename={$filename}.json");
        }
        
        // Convert to CSV
        $csv = $this->convertToCSV($results);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}.csv");
    }

    /**
     * Get predefined reports (common filter combinations)
     */
    public function predefinedReports()
    {
        $this->authorize('view_analytics');
        
        $reports = [
            'women_in_fisheries' => [
                'name' => 'Women in Fisheries',
                'filters' => ['gender' => 'female', 'farm_type' => 'fisheries'],
            ],
            'youth_cassava_farmers' => [
                'name' => 'Youth Cassava Farmers',
                'filters' => ['crop_type' => 'cassava', 'age_range' => ['min' => 18, 'max' => 35]],
            ],
            'cooperative_rice_farmers' => [
                'name' => 'Cooperative Rice Farmers',
                'filters' => ['crop_type' => 'rice', 'cooperative_member' => true],
            ],
            'educated_livestock_farmers' => [
                'name' => 'Educated Livestock Farmers',
                'filters' => [
                    'farm_type' => 'livestock', 
                    'educational_level' => ['tertiary', 'vocational']
                ],
            ],
            'fulltime_women_farmers' => [
                'name' => 'Full-time Women Farmers',
                'filters' => [
                    'gender' => 'female',
                    'primary_occupation' => 'full_time_farmer'
                ],
            ],
        ];
        
        return view('analytics.predefined-reports', compact('reports'));
    }

    /**
     * Run predefined report
     */
    public function runPredefinedReport(Request $request, string $reportKey)
    {
        $this->authorize('view_analytics');
        
        $reports = [
            'women_in_fisheries' => ['gender' => 'female', 'farm_type' => 'fisheries'],
            'youth_cassava_farmers' => ['crop_type' => 'cassava', 'age_range' => ['min' => 18, 'max' => 35]],
            'cooperative_rice_farmers' => ['crop_type' => 'rice', 'cooperative_member' => true],
            'educated_livestock_farmers' => ['farm_type' => 'livestock', 'educational_level' => ['tertiary', 'vocational']],
            'fulltime_women_farmers' => ['gender' => 'female', 'primary_occupation' => 'full_time_farmer'],
        ];
        
        if (!isset($reports[$reportKey])) {
            abort(404, 'Report not found');
        }
        
        $filters = $reports[$reportKey];
        
        // Allow LGA filtering for LGA-level users
        $user = auth()->user();
        if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
            $filters['lga_id'] = $user->administrative_id;
        }
        
        $results = $this->service->getFilteredAnalytics($filters);
        
        if ($request->expectsJson()) {
            return response()->json($results);
        }
        
        $filterOptions = $this->getFilterOptions();
        return view('analytics.advanced-results', compact('results', 'filters', 'filterOptions'));
    }

    /**
     * Get comparative analysis (compare multiple filter sets)
     */
    public function comparative(Request $request)
    {
        $this->authorize('view_analytics');
        
        // Example: Compare male vs female farmers in rice production
        $comparisons = [];
        
        $scenarios = $request->input('scenarios', [
            ['name' => 'Male Rice Farmers', 'filters' => ['gender' => 'male', 'crop_type' => 'rice']],
            ['name' => 'Female Rice Farmers', 'filters' => ['gender' => 'female', 'crop_type' => 'rice']],
        ]);
        
        foreach ($scenarios as $scenario) {
            $comparisons[$scenario['name']] = $this->service->getFilteredAnalytics($scenario['filters']);
        }
        
        if ($request->expectsJson()) {
            return response()->json($comparisons);
        }
        
        return view('analytics.comparative', compact('comparisons'));
    }

    // ==================== Helper Methods ====================

    /**
     * Build filters array from request
     */
    protected function buildFiltersFromRequest(Request $request): array
    {
        $filters = [];
        
        // Basic filters
        if ($request->filled('gender')) {
            $filters['gender'] = $request->input('gender');
        }
        
        if ($request->filled('age_min') || $request->filled('age_max')) {
            $filters['age_range'] = [];
            if ($request->filled('age_min')) {
                $filters['age_range']['min'] = (int) $request->input('age_min');
            }
            if ($request->filled('age_max')) {
                $filters['age_range']['max'] = (int) $request->input('age_max');
            }
        }
        
        if ($request->filled('educational_level')) {
            $filters['educational_level'] = $request->input('educational_level');
        }
        
        if ($request->filled('marital_status')) {
            $filters['marital_status'] = $request->input('marital_status');
        }
        
        if ($request->filled('primary_occupation')) {
            $filters['primary_occupation'] = $request->input('primary_occupation');
        }
        
        if ($request->filled('lga_id')) {
            $filters['lga_id'] = $request->input('lga_id');
        }
        
        if ($request->filled('cooperative_member')) {
            $filters['cooperative_member'] = $request->boolean('cooperative_member');
        }
        
        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }
        
        // Farm filters
        if ($request->filled('farm_type')) {
            $filters['farm_type'] = $request->input('farm_type');
        }
        
        if ($request->filled('ownership_status')) {
            $filters['ownership_status'] = $request->input('ownership_status');
        }
        
        // Crop filters
        if ($request->filled('crop_type')) {
            $filters['crop_type'] = $request->input('crop_type');
        }
        
        if ($request->filled('farming_method')) {
            $filters['farming_method'] = $request->input('farming_method');
        }
        
        // Livestock filters
        if ($request->filled('animal_type')) {
            $filters['animal_type'] = $request->input('animal_type');
        }
        
        if ($request->filled('breeding_practice')) {
            $filters['breeding_practice'] = $request->input('breeding_practice');
        }
        
        // Fisheries filters
        if ($request->filled('fishing_type')) {
            $filters['fishing_type'] = $request->input('fishing_type');
        }
        
        if ($request->filled('species_raised')) {
            $filters['species_raised'] = $request->input('species_raised');
        }
        
        return $filters;
    }

    /**
     * Get available options for all filters
     */
    protected function getFilterOptions(): array
    {
        return [
            'lgas' => DB::table('lgas')->select('id', 'name')->orderBy('name')->get(),
            'crop_types' => DB::table('crop_practice_details')
                ->distinct()
                ->pluck('crop_type')
                ->sort()
                ->values(),
            'animal_types' => DB::table('livestock_practice_details')
                ->distinct()
                ->pluck('animal_type')
                ->sort()
                ->values(),
            'species' => DB::table('fisheries_practice_details')
                ->distinct()
                ->pluck('species_raised')
                ->sort()
                ->values(),
        ];
    }

    /**
     * Convert results to CSV
     */
    protected function convertToCSV(array $results): string
    {
        $output = fopen('php://temp', 'r+');
        
        // Summary section
        fputcsv($output, ['SUMMARY']);
        foreach ($results['summary'] as $key => $value) {
            fputcsv($output, [$key, is_array($value) ? json_encode($value) : $value]);
        }
        fputcsv($output, []);
        
        // Demographics section
        fputcsv($output, ['DEMOGRAPHICS']);
        foreach ($results['demographics'] as $category => $data) {
            fputcsv($output, [ucwords(str_replace('_', ' ', $category))]);
            foreach ($data as $key => $value) {
                fputcsv($output, [$key, $value]);
            }
            fputcsv($output, []);
        }
        
        // Detailed results
        if (!empty($results['detailed_results'])) {
            fputcsv($output, ['DETAILED FARMER RESULTS']);
            
            if (count($results['detailed_results']) > 0) {
                $firstRow = (array) $results['detailed_results'][0];
                fputcsv($output, array_keys($firstRow));
                
                foreach ($results['detailed_results'] as $row) {
                    fputcsv($output, (array) $row);
                }
            }
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}

