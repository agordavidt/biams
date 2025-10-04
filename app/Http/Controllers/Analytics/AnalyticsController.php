<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Repositories\AnalyticsRepository;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnalyticsController extends Controller
{
    protected AnalyticsRepository $repository;
    protected AnalyticsService $service;

    public function __construct(AnalyticsRepository $repository, AnalyticsService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Main analytics dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->roles->first()->name ?? 'User';
        
        // Determine LGA scope based on role
        $lgaId = $this->determineLGAScope($user, $role);
        
        // Get dashboard data based on role
        $data = $this->repository->getDashboardData($role, $lgaId);
        
        return view('analytics.dashboard', compact('data', 'role'));
    }

    /**
     * Get farmer demographics
     */
    public function demographics(Request $request)
    {
        $this->authorize('view_analytics');
        
        $lgaId = $this->getLGAFromRequest($request);
        $data = $this->repository->getFarmerDemographics($lgaId);
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('analytics.demographics', compact('data'));
    }

    /**
     * Get farm production analytics
     */
    public function production(Request $request)
    {
        $this->authorize('view_analytics');
        
        $lgaId = $this->getLGAFromRequest($request);
        $data = $this->repository->getFarmProduction($lgaId);
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('analytics.production', compact('data'));
    }

    /**
     * Get crop analytics
     */
    public function crops(Request $request)
    {
        $this->authorize('view_analytics');
        
        $lgaId = $this->getLGAFromRequest($request);
        $cropType = $request->input('crop_type');
        
        $data = $this->repository->getCropProduction($lgaId, $cropType);
        $topCrops = $this->repository->getTopCropsByYield($lgaId, 10);
        
        if ($request->expectsJson()) {
            return response()->json([
                'crops' => $data,
                'top_crops' => $topCrops,
            ]);
        }
        
        return view('analytics.crops', compact('data', 'topCrops'));
    }

    /**
     * Get livestock analytics
     */
    public function livestock(Request $request)
    {
        $this->authorize('view_analytics');
        
        $lgaId = $this->getLGAFromRequest($request);
        $animalType = $request->input('animal_type');
        
        $data = $this->repository->getLivestockProduction($lgaId, $animalType);
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('analytics.livestock', compact('data'));
    }

    /**
     * Get cooperative engagement analytics
     */
    public function cooperatives(Request $request)
    {
        $this->authorize('view_analytics');
        
        $lgaId = $this->getLGAFromRequest($request);
        $data = $this->repository->getCooperativeEngagement($lgaId);
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('analytics.cooperatives', compact('data'));
    }

    /**
     * Get enrollment pipeline analytics
     */
    public function enrollment(Request $request)
    {
        $this->authorize('view_analytics');
        
        $user = auth()->user();
        $lgaId = $this->getLGAFromRequest($request);
        
        // If enrollment agent, filter by their enrollments
        $enrolledBy = null;
        if ($user->hasRole('Enrollment Agent')) {
            $enrolledBy = $user->id;
        }
        
        $data = $this->repository->getEnrollmentPipeline($lgaId, $enrolledBy);
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('analytics.enrollment', compact('data'));
    }

    /**
     * Get trend data for charts
     */
    public function trends(Request $request)
    {
        $this->authorize('view_analytics');
        
        $metric = $request->input('metric', 'farmer_growth');
        $days = $request->input('days', 30);
        $lgaId = $this->getLGAFromRequest($request);
        
        $data = $this->repository->getTrendData($metric, $lgaId, $days);
        
        return response()->json($data);
    }

    /**
     * Get LGA comparison (State-level only)
     */
    public function lgaComparison(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasAnyRole(['Super Admin', 'Governor', 'State Admin'])) {
            abort(403, 'Unauthorized access to state-wide comparison');
        }
        
        $data = $this->repository->getLGAComparison();
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('analytics.lga-comparison', compact('data'));
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $this->authorize('export_analytics');
        
        $reportType = $request->input('type', 'comprehensive');
        $lgaId = $this->getLGAFromRequest($request);
        
        $data = $this->repository->getExportData($reportType, $lgaId);
        
        // Generate filename with timestamp
        $timestamp = now()->format('Y-m-d_His');
        $lgaName = $lgaId ? \App\Models\LGA::find($lgaId)->code : 'STATE';
        $filename = "{$reportType}_analytics_{$lgaName}_{$timestamp}";
        
        // Return as CSV or JSON based on request
        $format = $request->input('format', 'csv');
        
        if ($format === 'json') {
            return response()->json($data)
                ->header('Content-Disposition', "attachment; filename={$filename}.json");
        }
        
        // Convert to CSV
        $csv = $this->convertToCSV($data, $reportType);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}.csv");
    }

    /**
     * Manually trigger analytics generation (Admin only)
     */
    public function regenerate(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'State Admin'])) {
            abort(403, 'Only administrators can regenerate analytics');
        }
        
        $lgaId = $request->input('lga_id');
        
        try {
            $this->service->generateDailySnapshot($lgaId);
            
            return response()->json([
                'success' => true,
                'message' => 'Analytics regenerated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to regenerate analytics: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get agent performance (LGA Admin only)
     */
    public function agentPerformance(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('LGA Admin')) {
            abort(403, 'Only LGA Admins can view agent performance');
        }
        
        $lgaId = $user->administrative_id;
        $data = $this->repository->getAgentsPerformance($lgaId);
        
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('analytics.agent-performance', compact('data'));
    }

    // ==================== Helper Methods ====================

    /**
     * Determine LGA scope based on user role
     */
    protected function determineLGAScope($user, string $role): ?int
    {
        if (in_array($role, ['Super Admin', 'Governor', 'State Admin'])) {
            return null; // State-wide access
        }
        
        if (in_array($role, ['LGA Admin', 'Enrollment Agent'])) {
            return $user->administrative_id;
        }
        
        return null;
    }

    /**
     * Get LGA from request with authorization check
     */
    protected function getLGAFromRequest(Request $request): ?int
    {
        $user = auth()->user();
        $requestedLgaId = $request->input('lga_id');
        
        // State-level users can view any LGA
        if ($user->hasAnyRole(['Super Admin', 'Governor', 'State Admin'])) {
            return $requestedLgaId;
        }
        
        // LGA-level users can only view their own LGA
        if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
            return $user->administrative_id;
        }
        
        return null;
    }

    /**
     * Convert data array to CSV format
     */
    protected function convertToCSV(array $data, string $reportType): string
    {
        if (empty($data)) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Handle different report structures
        if ($reportType === 'comprehensive') {
            // Write each section with headers
            foreach ($data as $section => $rows) {
                if (empty($rows)) continue;
                
                fputcsv($output, [strtoupper($section)]);
                
                $firstRow = is_array($rows) ? reset($rows) : $rows;
                fputcsv($output, array_keys((array)$firstRow));
                
                foreach ($rows as $row) {
                    fputcsv($output, (array)$row);
                }
                
                fputcsv($output, []); // Blank line between sections
            }
        } else {
            // Simple flat structure
            $firstRow = reset($data);
            fputcsv($output, array_keys((array)$firstRow));
            
            foreach ($data as $row) {
                fputcsv($output, (array)$row);
            }
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}