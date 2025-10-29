<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceApplication;
use App\Models\Resource;
use App\Models\Payment;
use App\Notifications\ResourceStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResourceApplicationController extends Controller
{
    /**
     * Display all applications (Admin oversight view)
     */
    public function index(Request $request)
    {
        $query = ResourceApplication::with([
            'user', 
            'farmer', 
            'resource.vendor', 
            'resource.partner',
            'reviewedBy',
            'fulfilledBy',
            'payment'
        ]);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by resource
        if ($request->resource_id) {
            $query->where('resource_id', $request->resource_id);
        }

        // Filter by vendor
        if ($request->vendor_id) {
            $query->whereHas('resource', function($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            });
        }

        // Filter by payment status
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by user name, email, or payment reference
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('farmer', function($farmerQuery) use ($search) {
                    $farmerQuery->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('phone_number', 'like', "%$search%")
                        ->orWhere('nin', 'like', "%$search%");
                })
                ->orWhere('payment_reference', 'like', "%$search%");
            });
        }

        // Date range filter
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        // Comprehensive statistics
        $stats = [
            'total' => ResourceApplication::count(),
            'pending' => ResourceApplication::where('status', 'pending')->count(),
            'approved' => ResourceApplication::where('status', 'approved')->count(),
            'payment_pending' => ResourceApplication::where('status', 'payment_pending')->count(),
            'paid' => ResourceApplication::where('status', 'paid')->count(),
            'fulfilled' => ResourceApplication::where('status', 'fulfilled')->count(),
            'rejected' => ResourceApplication::where('status', 'rejected')->count(),
            'cancelled' => ResourceApplication::where('status', 'cancelled')->count(),
            'total_revenue' => ResourceApplication::whereIn('status', ['paid', 'fulfilled'])->sum('amount_paid'),
        ];

        // Vendor performance overview
        $vendorStats = DB::table('resource_applications')
            ->join('resources', 'resource_applications.resource_id', '=', 'resources.id')
            ->join('vendors', 'resources.vendor_id', '=', 'vendors.id')
            ->select(
                'vendors.id',
                'vendors.business_name',
                DB::raw('COUNT(resource_applications.id) as total_applications'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "paid" THEN 1 ELSE 0 END) as paid_count'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "fulfilled" THEN 1 ELSE 0 END) as fulfilled_count'),
                DB::raw('SUM(CASE WHEN resource_applications.status IN ("paid", "fulfilled") THEN resource_applications.amount_paid ELSE 0 END) as total_revenue')
            )
            ->groupBy('vendors.id', 'vendors.business_name')
            ->having('total_applications', '>', 0)
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Resource statistics
        $resourceStats = Resource::query()
            ->withCount([
                'applications as total_applications',
                'applications as approved_count' => fn($q) => $q->where('status', 'approved'),
                'applications as paid_count' => fn($q) => $q->where('status', 'paid'),
                'applications as fulfilled_count' => fn($q) => $q->where('status', 'fulfilled'),
                'applications as pending_count' => fn($q) => $q->where('status', 'pending'),
            ])
            ->having('total_applications', '>', 0)
            ->get()
            ->keyBy('id');

        $resources = Resource::active()->get();

        return view('admin.resources.applications.index', compact(
            'applications', 
            'stats', 
            'resourceStats', 
            'resources',
            'vendorStats'
        ));
    }

    /**
     * Show specific application (Admin can view all details)
     */
    public function show(ResourceApplication $application)
    {
        $application->load([
            'user', 
            'farmer', 
            'resource.vendor', 
            'resource.partner',
            'reviewedBy',
            'fulfilledBy',
            'processedBy',
            'payment'
        ]);
        
        $statusOptions = ResourceApplication::getStatusOptions();
        
        // Get payment verification details
        $paymentVerified = false;
        if ($application->payment_reference) {
            $paymentVerified = Payment::where('reference', $application->payment_reference)
                ->where('status', 'success')
                ->exists();
        }
        
        return view('admin.resources.applications.show', compact(
            'application', 
            'statusOptions', 
            'paymentVerified'
        ));
    }

    /**
     * Admin override: Approve application (Only when vendor hasn't acted)
     * This should be rare - primarily for ministry resources or urgent cases
     */
    public function approve(Request $request, ResourceApplication $application)
    {
        // Check if this is a vendor resource
        if ($application->resource->vendor_id) {
            return back()->with('warning', 'This application should be approved by the vendor. Admin approval should only be used in exceptional cases.');
        }

        if (!$application->canBeApproved()) {
            return back()->with('error', 'This application cannot be approved at this time.');
        }

        $rules = [
            'admin_notes' => 'nullable|string|max:500'
        ];

        // Add quantity validation if resource requires it
        if ($application->resource->requires_quantity) {
            $rules['quantity_approved'] = 'required|integer|min:1|max:' . $application->quantity_requested;
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            $quantityApproved = $application->resource->requires_quantity 
                ? $validated['quantity_approved'] 
                : null;

            // Check stock availability for physical resources
            if ($application->resource->requires_quantity) {
                if ($application->resource->available_stock < $quantityApproved) {
                    return back()->with('error', 'Insufficient stock available. Only ' . $application->resource->available_stock . ' units remaining.');
                }

                // Decrement stock
                $application->resource->decrementStock($quantityApproved);
            }

            // Approve the application
            $application->update([
                'status' => $application->resource->requires_payment ? 'payment_pending' : 'approved',
                'quantity_approved' => $quantityApproved,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);
            
            // Notify user
            try {
                $application->user->notify(
                    new ResourceStatusUpdated($application, $validated['admin_notes'] ?? null)
                );
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage(), [
                    'user_id' => $application->user->id,
                    'application_id' => $application->id,
                ]);
            }

            DB::commit();

            $message = $application->resource->requires_payment
                ? 'Application approved. User will be notified to make payment.'
                : 'Application approved. User will be notified.';

            return redirect()->route('admin.resources.applications.show', $application)
                ->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Admin application approval failed: ' . $e->getMessage(), [
                'application_id' => $application->id
            ]);
            
            return back()->with('error', 'Failed to approve application. Please try again.');
        }
    }

    /**
     * Admin override: Reject application (Can intervene if vendor hasn't acted)
     */
    public function reject(Request $request, ResourceApplication $application)
    {
        if (!$application->canBeRejected()) {
            return back()->with('error', 'This application cannot be rejected at this time.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500|min:10'
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejecting this application.',
            'rejection_reason.min' => 'Rejection reason must be at least 10 characters.'
        ]);

        try {
            $application->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
            
            // Notify user
            try {
                $application->user->notify(
                    new ResourceStatusUpdated($application, $validated['rejection_reason'])
                );
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage(), [
                    'user_id' => $application->user->id,
                    'application_id' => $application->id,
                ]);
            }

            return redirect()->route('admin.resources.applications.show', $application)
                ->with('success', 'Application rejected. User has been notified.');
            
        } catch (\Exception $e) {
            Log::error('Admin application rejection failed: ' . $e->getMessage(), [
                'application_id' => $application->id
            ]);
            
            return back()->with('error', 'Failed to reject application. Please try again.');
        }
    }

    /**
     * Admin override: Mark application as fulfilled (Only if vendor hasn't fulfilled)
     */
    public function fulfill(Request $request, ResourceApplication $application)
    {
        // Warn if this is a vendor resource
        if ($application->resource->vendor_id) {
            return back()->with('warning', 'This application should be fulfilled by the vendor. Use admin fulfillment only in exceptional cases.');
        }

        if (!$application->canBeFulfilled()) {
            return back()->with('error', 'This application cannot be fulfilled at this time.');
        }

        $validated = $request->validate([
            'fulfillment_notes' => 'nullable|string|max:500'
        ]);

        try {
            $quantityFulfilled = $application->resource->requires_quantity
                ? ($application->quantity_paid ?? $application->quantity_approved)
                : null;

            $application->update([
                'status' => 'fulfilled',
                'quantity_fulfilled' => $quantityFulfilled,
                'fulfilled_by' => Auth::id(),
                'fulfilled_at' => now(),
                'fulfillment_notes' => $validated['fulfillment_notes'] ?? null,
            ]);

            // Notify user
            try {
                $application->user->notify(
                    new ResourceStatusUpdated($application, $validated['fulfillment_notes'] ?? null)
                );
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }

            return redirect()->route('admin.resources.applications.show', $application)
                ->with('success', 'Application marked as fulfilled.');

        } catch (\Exception $e) {
            Log::error('Admin application fulfillment failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to mark application as fulfilled.');
        }
    }

    /**
     * Verify payment status (Admin tool to check payment)
     */
    public function verifyPayment(ResourceApplication $application)
    {
        if (!$application->payment_reference) {
            return response()->json([
                'success' => false,
                'message' => 'No payment reference found for this application'
            ], 400);
        }

        try {
            $payment = Payment::where('reference', $application->payment_reference)
                ->where('resourceId', $application->resource_id)
                ->where('customerId', $application->user_id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment record not found'
                ]);
            }

            $isVerified = $payment->status === 'success';

            return response()->json([
                'success' => true,
                'verified' => $isVerified,
                'payment' => [
                    'reference' => $payment->reference,
                    'amount' => $payment->transAmount,
                    'status' => $payment->status,
                    'date' => $payment->transDate,
                    'credo_reference' => $payment->credo_reference,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment'
            ], 500);
        }
    }

    /**
     * Analytics dashboard for admins
     */
    public function analytics(Request $request)
    {
        // Date range (default last 30 days)
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // Overall statistics
        $stats = ResourceApplication::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN status = "fulfilled" THEN 1 ELSE 0 END) as fulfilled,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status IN ("paid", "fulfilled") THEN amount_paid ELSE 0 END) as total_revenue,
                AVG(TIMESTAMPDIFF(HOUR, created_at, reviewed_at)) as avg_approval_time_hours,
                AVG(TIMESTAMPDIFF(HOUR, reviewed_at, fulfilled_at)) as avg_fulfillment_time_hours
            ')
            ->first();

        // Vendor performance comparison
        $vendorPerformance = DB::table('resource_applications')
            ->join('resources', 'resource_applications.resource_id', '=', 'resources.id')
            ->join('vendors', 'resources.vendor_id', '=', 'vendors.id')
            ->whereBetween('resource_applications.created_at', [$dateFrom, $dateTo])
            ->select(
                'vendors.id',
                'vendors.business_name',
                DB::raw('COUNT(resource_applications.id) as total_applications'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "paid" THEN 1 ELSE 0 END) as paid_count'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "fulfilled" THEN 1 ELSE 0 END) as fulfilled_count'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, resource_applications.created_at, resource_applications.reviewed_at)) as avg_response_time'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, resource_applications.reviewed_at, resource_applications.fulfilled_at)) as avg_fulfillment_time'),
                DB::raw('SUM(CASE WHEN resource_applications.status IN ("paid", "fulfilled") THEN resource_applications.amount_paid ELSE 0 END) as total_revenue')
            )
            ->groupBy('vendors.id', 'vendors.business_name')
            ->orderByDesc('total_revenue')
            ->get();

        // Applications by status over time (daily breakdown)
        $dailyStats = DB::table('resource_applications')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total,
                SUM(CASE WHEN status = "fulfilled" THEN 1 ELSE 0 END) as fulfilled,
                SUM(CASE WHEN status IN ("paid", "fulfilled") THEN amount_paid ELSE 0 END) as revenue
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Resource type distribution
        $resourceTypeStats = DB::table('resource_applications')
            ->join('resources', 'resource_applications.resource_id', '=', 'resources.id')
            ->whereBetween('resource_applications.created_at', [$dateFrom, $dateTo])
            ->select(
                'resources.type',
                DB::raw('COUNT(resource_applications.id) as count'),
                DB::raw('SUM(CASE WHEN resource_applications.status = "fulfilled" THEN 1 ELSE 0 END) as fulfilled_count'),
                DB::raw('SUM(resource_applications.amount_paid) as total_revenue')
            )
            ->groupBy('resources.type')
            ->get();

        return view('admin.resources.applications.analytics', compact(
            'stats',
            'vendorPerformance',
            'dailyStats',
            'resourceTypeStats',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Bulk update applications (Admin emergency tool)
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'applications' => 'required|array|min:1',
            'applications.*' => 'exists:resource_applications,id', 
            'action' => 'required|in:approve,reject,fulfill',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validated['action'] === 'reject' && empty($validated['notes'])) {
            return back()->withErrors(['notes' => 'Notes are required when rejecting applications.']);
        }

        $updatedCount = 0;
        $failedCount = 0;
        $vendorResourceSkipped = 0;
        $applications = ResourceApplication::with('resource')
            ->whereIn('id', $validated['applications'])
            ->get();

        DB::beginTransaction();

        try {
            foreach ($applications as $application) {
                // Skip vendor resources - they should handle their own
                if ($application->resource->vendor_id) {
                    $vendorResourceSkipped++;
                    continue;
                }

                try {
                    if ($validated['action'] === 'approve' && $application->canBeApproved()) {
                        $application->update([
                            'status' => 'approved',
                            'reviewed_by' => Auth::id(),
                            'reviewed_at' => now(),
                            'admin_notes' => $validated['notes'] ?? null,
                        ]);
                        $updatedCount++;
                    } elseif ($validated['action'] === 'reject' && $application->canBeRejected()) {
                        $application->update([
                            'status' => 'rejected',
                            'rejection_reason' => $validated['notes'],
                            'reviewed_by' => Auth::id(),
                            'reviewed_at' => now(),
                        ]);
                        $updatedCount++;
                    } elseif ($validated['action'] === 'fulfill' && $application->canBeFulfilled()) {
                        $application->update([
                            'status' => 'fulfilled',
                            'fulfilled_by' => Auth::id(),
                            'fulfilled_at' => now(),
                            'fulfillment_notes' => $validated['notes'] ?? null,
                        ]);
                        $updatedCount++;
                    }

                    // Notify user
                    try {
                        $application->user->notify(
                            new ResourceStatusUpdated($application, $validated['notes'] ?? null)
                        );
                    } catch (\Exception $e) {
                        Log::error('Bulk notification failed: ' . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error('Bulk update failed for application: ' . $e->getMessage(), [
                        'application_id' => $application->id
                    ]);
                }
            }

            DB::commit();

            $message = "Successfully updated {$updatedCount} application(s)";
            if ($vendorResourceSkipped > 0) {
                $message .= ". {$vendorResourceSkipped} vendor application(s) skipped (vendors should handle these).";
            }
            if ($failedCount > 0) {
                $message .= ". {$failedCount} application(s) could not be updated.";
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Bulk update failed. Please try again.');
        }
    }

    /**
     * Export applications to CSV
     */
    public function export(Request $request)
    {
        $applications = ResourceApplication::query()
            ->with(['user', 'farmer', 'resource.vendor', 'reviewedBy', 'fulfilledBy'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->vendor_id, fn($q, $vendorId) => $q->whereHas('resource', fn($rq) => $rq->where('vendor_id', $vendorId)))
            ->when($request->date_from, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->date_to, fn($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="resource-applications-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'User', 'Email', 'Phone', 'Farmer NIN', 'Resource', 'Vendor',
                'Quantity Requested', 'Quantity Approved', 'Quantity Fulfilled',
                'Amount Paid', 'Payment Reference', 'Status', 'Payment Status',
                'Reviewed By', 'Fulfilled By', 'Applied At', 'Reviewed At', 'Fulfilled At'
            ]);

            // Data
            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->user->name,
                    $app->user->email,
                    $app->farmer ? $app->farmer->phone_number : ($app->user->phone ?? 'N/A'),
                    $app->farmer ? $app->farmer->nin : 'N/A',
                    $app->resource->name,
                    $app->resource->vendor ? $app->resource->vendor->business_name : 'Ministry',
                    $app->quantity_requested ?? 'N/A',
                    $app->quantity_approved ?? 'N/A',
                    $app->quantity_fulfilled ?? 'N/A',
                    $app->amount_paid ?? 0,
                    $app->payment_reference ?? 'N/A',
                    ucfirst($app->status),
                    $app->payment_status ? ucfirst($app->payment_status) : 'N/A',
                    $app->reviewedBy ? $app->reviewedBy->name : 'N/A',
                    $app->fulfilledBy ? $app->fulfilledBy->name : 'N/A',
                    $app->created_at->format('Y-m-d H:i'),
                    $app->reviewed_at ? $app->reviewed_at->format('Y-m-d H:i') : 'N/A',
                    $app->fulfilled_at ? $app->fulfilled_at->format('Y-m-d H:i') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}