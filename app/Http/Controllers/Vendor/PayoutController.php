<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use App\Models\VendorPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayoutController extends Controller
{
    /**
     * Display payout dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Payout summary
        $payoutSummary = $this->getPayoutSummary($vendor);

        // Pending reimbursements
        $pendingReimbursements = $this->getPendingReimbursements($vendor);

        // Payout history
        $payoutHistory = VendorPayout::where('vendor_id', $vendor->id)
            ->with('processedBy')
            ->latest()
            ->paginate(20);

        // Monthly breakdown
        $monthlyBreakdown = $this->getMonthlyBreakdown($vendor);

        return view('vendor.payouts.index', compact(
            'vendor',
            'payoutSummary',
            'pendingReimbursements',
            'payoutHistory',
            'monthlyBreakdown'
        ));
    }

    /**
     * Get payout summary
     */
    private function getPayoutSummary($vendor)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        // Total expected reimbursement
        $totalExpected = ResourceApplication::whereIn('resource_id', $resourceIds)
            ->where('status', 'paid')
            ->with('resource')
            ->get()
            ->sum(function($app) {
                return ($app->quantity_paid ?? 0) * ($app->resource->vendor_reimbursement ?? 0);
            });

        // Total paid out
        $totalPaid = VendorPayout::where('vendor_id', $vendor->id)
            ->where('status', 'completed')
            ->sum('amount');

        // Pending payouts
        $pendingAmount = VendorPayout::where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Processing payouts
        $processingAmount = VendorPayout::where('vendor_id', $vendor->id)
            ->where('status', 'processing')
            ->sum('amount');

        return [
            'total_expected' => $totalExpected,
            'total_paid' => $totalPaid,
            'pending_reimbursement' => $totalExpected - $totalPaid - $pendingAmount - $processingAmount,
            'pending_payout' => $pendingAmount,
            'processing_payout' => $processingAmount,
        ];
    }

    /**
     * Get pending reimbursements (fulfilled applications not yet paid)
     */
    private function getPendingReimbursements($vendor)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        // Get all paid/fulfilled applications
        $applications = ResourceApplication::whereIn('resource_id', $resourceIds)
            ->whereIn('status', ['paid', 'fulfilled'])
            ->with('resource')
            ->get();

        // Get application IDs that have been included in payouts
        $paidApplicationIds = DB::table('vendor_payout_items')
            ->whereIn('vendor_payout_id', function($query) use ($vendor) {
                $query->select('id')
                    ->from('vendor_payouts')
                    ->where('vendor_id', $vendor->id);
            })
            ->pluck('resource_application_id')
            ->toArray();

        // Filter out applications already included in payouts
        $pendingApps = $applications->whereNotIn('id', $paidApplicationIds);

        return $pendingApps->map(function($app) {
            $reimbursementAmount = ($app->quantity_paid ?? 0) * ($app->resource->vendor_reimbursement ?? 0);
            return [
                'id' => $app->id,
                'resource_name' => $app->resource->name,
                'farmer_name' => $app->user->name,
                'quantity' => $app->quantity_paid,
                'unit' => $app->resource->unit,
                'unit_reimbursement' => $app->resource->vendor_reimbursement,
                'total_reimbursement' => $reimbursementAmount,
                'fulfilled_at' => $app->fulfilled_at ?? $app->paid_at,
            ];
        })->values();
    }

    /**
     * Get monthly breakdown
     */
    private function getMonthlyBreakdown($vendor)
    {
        $resourceIds = $vendor->resources()->pluck('id');

        $last6Months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $applications = ResourceApplication::whereIn('resource_id', $resourceIds)
                ->where('status', 'paid')
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->with('resource')
                ->get();

            $expectedReimbursement = $applications->sum(function($app) {
                return ($app->quantity_paid ?? 0) * ($app->resource->vendor_reimbursement ?? 0);
            });

            $paidOut = VendorPayout::where('vendor_id', $vendor->id)
                ->where('status', 'completed')
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->sum('amount');

            $last6Months->push([
                'month' => $month->format('M Y'),
                'expected' => $expectedReimbursement,
                'paid' => $paidOut,
                'pending' => $expectedReimbursement - $paidOut,
            ]);
        }

        return $last6Months;
    }

    /**
     * Request payout
     */
    public function request(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return back()->with('error', 'No vendor account found.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_account_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if requested amount is available
        $summary = $this->getPayoutSummary($vendor);
        if ($request->amount > $summary['pending_reimbursement']) {
            return back()->with('error', 'Requested amount exceeds available balance.');
        }

        try {
            DB::beginTransaction();

            // Create payout request
            $payout = VendorPayout::create([
                'vendor_id' => $vendor->id,
                'amount' => $request->amount,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_name' => $request->bank_name,
                'notes' => $request->notes,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('vendor.payouts')
                ->with('success', 'Payout request submitted successfully. You will be notified once processed.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Error submitting payout request: ' . $e->getMessage());
        }
    }

    /**
     * Show payout details
     */
    public function show(VendorPayout $payout)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure payout belongs to this vendor
        if ($payout->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.payouts')
                ->with('error', 'Unauthorized access.');
        }

        $payout->load(['items.application.resource', 'processedBy']);

        return view('vendor.payouts.show', compact('vendor', 'payout'));
    }

    /**
     * Export payout history
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $payouts = VendorPayout::where('vendor_id', $vendor->id)
            ->with('processedBy')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="vendor-payouts-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($payouts) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Date Requested', 'Amount', 'Status', 'Bank', 'Account Number', 
                'Processed By', 'Date Paid', 'Reference'
            ]);

            foreach ($payouts as $payout) {
                fputcsv($file, [
                    $payout->requested_at->format('Y-m-d'),
                    $payout->amount,
                    ucfirst($payout->status),
                    $payout->bank_name,
                    $payout->bank_account_number,
                    $payout->processedBy->name ?? 'N/A',
                    $payout->paid_at ? $payout->paid_at->format('Y-m-d') : 'N/A',
                    $payout->payment_reference ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}