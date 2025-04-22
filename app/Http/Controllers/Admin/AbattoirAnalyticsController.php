<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abattoir;
use App\Models\Livestock;
use App\Models\SlaughterOperation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbattoirAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Get all LGAs for filter dropdown
        $lgas = Livestock::select('origin_lga')
            ->distinct()
            ->orderBy('origin_lga')
            ->pluck('origin_lga');

        // Get all abattoirs for filter dropdown
        $abattoirs = Abattoir::select('id', 'name')
            ->orderBy('name')
            ->get();

        // Get filter parameters
        $selectedLga = $request->lga;
        $selectedAbattoir = $request->abattoir_id;
        $dateRange = $request->date_range ?? 'month'; // Default to monthly
        $startDate = null;
        $endDate = null;
        
        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        } else {
            // Set default date range based on selection
            switch ($dateRange) {
                case 'day':
                    $startDate = Carbon::today();
                    $endDate = Carbon::today()->endOfDay();
                    break;
                case 'week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'month':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'year':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
            }
        }

        // Get livestock registration data
        $livestockRegistrationQuery = Livestock::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        );

        // Apply LGA filter if selected
        if ($selectedLga) {
            $livestockRegistrationQuery->where('origin_lga', $selectedLga);
        }

        // Apply date range filter
        $livestockRegistrationQuery->whereBetween('created_at', [$startDate, $endDate]);
        
        // Group by date
        $livestockRegistrationData = $livestockRegistrationQuery->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get slaughter operation data
        $slaughterOperationsQuery = SlaughterOperation::select(
            DB::raw('DATE(slaughter_date) as date'),
            DB::raw('COUNT(*) as count')
        );

        // Apply abattoir filter if selected
        if ($selectedAbattoir) {
            $slaughterOperationsQuery->where('abattoir_id', $selectedAbattoir);
        }

        // Apply date range filter
        $slaughterOperationsQuery->whereBetween('slaughter_date', [$startDate, $endDate]);
        
        // Group by date
        $slaughterOperationsData = $slaughterOperationsQuery->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for species distribution chart
        $speciesDistribution = Livestock::select('species', DB::raw('COUNT(*) as count'))
            ->when($selectedLga, function($query) use ($selectedLga) {
                return $query->where('origin_lga', $selectedLga);
            })
            ->groupBy('species')
            ->orderBy('count', 'desc')
            ->get();

        // Prepare data for LGA distribution chart
        $lgaDistribution = Livestock::select('origin_lga', DB::raw('COUNT(*) as count'))
            ->groupBy('origin_lga')
            ->orderBy('count', 'desc')
            ->get();

        // Get summary statistics
        $totalRegisteredLivestock = Livestock::when($selectedLga, function($query) use ($selectedLga) {
            return $query->where('origin_lga', $selectedLga);
        })->count();

        $totalSlaughteredLivestock = SlaughterOperation::when($selectedAbattoir, function($query) use ($selectedAbattoir) {
            return $query->where('abattoir_id', $selectedAbattoir);
        })->count();

        $pendingSlaughter = Livestock::whereIn('status', ['registered', 'inspected', 'approved'])
            ->when($selectedLga, function($query) use ($selectedLga) {
                return $query->where('origin_lga', $selectedLga);
            })
            ->count();

        $rejectedLivestock = Livestock::where('status', 'rejected')
            ->when($selectedLga, function($query) use ($selectedLga) {
                return $query->where('origin_lga', $selectedLga);
            })
            ->count();

        // Calculate meat production
        $meatProduction = SlaughterOperation::when($selectedAbattoir, function($query) use ($selectedAbattoir) {
            return $query->where('abattoir_id', $selectedAbattoir);
        })
        ->whereBetween('slaughter_date', [$startDate, $endDate])
        ->sum('carcass_weight_kg');

        // Get meat grade distribution
        $meatGradeDistribution = SlaughterOperation::select('meat_grade', DB::raw('COUNT(*) as count'))
            ->when($selectedAbattoir, function($query) use ($selectedAbattoir) {
                return $query->where('abattoir_id', $selectedAbattoir);
            })
            ->whereBetween('slaughter_date', [$startDate, $endDate])
            ->groupBy('meat_grade')
            ->orderBy('count', 'desc')
            ->get();

        // Format data for charts
        $registrationChartData = $this->formatChartData($livestockRegistrationData);
        $slaughterChartData = $this->formatChartData($slaughterOperationsData);

        return view('admin.abattoirs.analytics', compact(
            'lgas',
            'abattoirs',
            'dateRange',
            'selectedLga',
            'selectedAbattoir',
            'startDate',
            'endDate',
            'registrationChartData',
            'slaughterChartData',
            'speciesDistribution',
            'lgaDistribution',
            'totalRegisteredLivestock',
            'totalSlaughteredLivestock',
            'pendingSlaughter',
            'rejectedLivestock',
            'meatProduction',
            'meatGradeDistribution'
        ));
    }

    // Generate PDF report
    public function generateReport(Request $request)
    {
        $lga = $request->lga;
        $abattoirId = $request->abattoir_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subMonth()->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // Get abattoir details if filter is applied
        $abattoir = $abattoirId ? Abattoir::find($abattoirId) : null;

        // Livestock registration statistics
        $livestockQuery = Livestock::whereBetween('created_at', [$startDate, $endDate]);
        if ($lga) {
            $livestockQuery->where('origin_lga', $lga);
        }
        $registeredLivestock = $livestockQuery->count();

        // Slaughter operation statistics
        $slaughterQuery = SlaughterOperation::whereBetween('slaughter_date', [$startDate, $endDate]);
        if ($abattoirId) {
            $slaughterQuery->where('abattoir_id', $abattoirId);
        }
        $slaughterOperations = $slaughterQuery->count();
        
        // Daily breakdown of slaughter operations
        $dailyStats = SlaughterOperation::select(
            DB::raw('DATE(slaughter_date) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(carcass_weight_kg) as total_weight')
        )
        ->whereBetween('slaughter_date', [$startDate, $endDate])
        ->when($abattoirId, function($query) use ($abattoirId) {
            return $query->where('abattoir_id', $abattoirId);
        })
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Species breakdown
        $speciesStats = SlaughterOperation::select(
            'livestock.species',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(slaughter_operations.carcass_weight_kg) as total_weight')
        )
        ->join('livestock', 'slaughter_operations.livestock_id', '=', 'livestock.id')
        ->whereBetween('slaughter_date', [$startDate, $endDate])
        ->when($abattoirId, function($query) use ($abattoirId) {
            return $query->where('slaughter_operations.abattoir_id', $abattoirId);
        })
        ->when($lga, function($query) use ($lga) {
            return $query->where('livestock.origin_lga', $lga);
        })
        ->groupBy('livestock.species')
        ->get();

        // Generate the PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.abattoirs.report', compact(
            'startDate', 
            'endDate', 
            'abattoir', 
            'lga', 
            'registeredLivestock', 
            'slaughterOperations', 
            'dailyStats', 
            'speciesStats'
        ));

        return $pdf->download('abattoir-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // Helper method to format chart data
    private function formatChartData($data)
    {
        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = Carbon::parse($item->date)->format('M d');
            $values[] = $item->count;
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    // Detailed Livestock Registration Report
    public function livestockReport(Request $request)
    {
        $lga = $request->lga;
        $status = $request->status;
        $species = $request->species;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $livestockQuery = Livestock::with('registeredBy');

        if ($lga) {
            $livestockQuery->where('origin_lga', $lga);
        }

        if ($status) {
            $livestockQuery->where('status', $status);
        }

        if ($species) {
            $livestockQuery->where('species', $species);
        }

        if ($startDate && $endDate) {
            $livestockQuery->whereBetween('registration_date', [$startDate, $endDate]);
        }

        $livestock = $livestockQuery->latest()->paginate(20);

        // For the filters
        $lgas = Livestock::select('origin_lga')->distinct()->orderBy('origin_lga')->pluck('origin_lga');
        $statuses = Livestock::select('status')->distinct()->pluck('status');
        $speciesList = Livestock::select('species')->distinct()->pluck('species');

        return view('admin.abattoirs.livestock_report', compact(
            'livestock', 
            'lgas', 
            'statuses', 
            'speciesList', 
            'lga', 
            'status', 
            'species', 
            'startDate', 
            'endDate'
        ));
    }

    // Detailed Slaughter Operations Report
    public function slaughterReport(Request $request)
    {
        $abattoirId = $request->abattoir_id;
        $meatGrade = $request->meat_grade;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $operationsQuery = SlaughterOperation::with(['livestock', 'abattoir', 'slaughteredBy', 'supervisedBy']);

        if ($abattoirId) {
            $operationsQuery->where('abattoir_id', $abattoirId);
        }

        if ($meatGrade) {
            $operationsQuery->where('meat_grade', $meatGrade);
        }

        if ($startDate && $endDate) {
            $operationsQuery->whereBetween('slaughter_date', [$startDate, $endDate]);
        }

        $operations = $operationsQuery->latest()->paginate(20);

        // For the filters
        $abattoirs = Abattoir::select('id', 'name')->orderBy('name')->get();
        $meatGrades = SlaughterOperation::select('meat_grade')->distinct()->pluck('meat_grade');

        return view('admin.abattoirs.slaughter_report', compact(
            'operations', 
            'abattoirs', 
            'meatGrades', 
            'abattoirId', 
            'meatGrade', 
            'startDate', 
            'endDate'
        ));
    }
}