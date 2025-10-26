<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApplicationManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = ResourceApplication::with(['resource.vendor', 'farmer', 'user', 'reviewedBy'])
            ->latest();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $applications = $query->get();
        
        $stats = [
            'total' => ResourceApplication::count(),
            'pending' => ResourceApplication::where('status', 'pending')->count(),
            'approved' => ResourceApplication::where('status', 'approved')->count(),
            'rejected' => ResourceApplication::where('status', 'rejected')->count(),
            'paid' => ResourceApplication::where('status', 'paid')->count(),
            'fulfilled' => ResourceApplication::where('status', 'fulfilled')->count(),
        ];
        
        return view('admin.applications.index', compact('applications', 'stats', 'status'));
    }

    public function show(ResourceApplication $application)
    {
        $application->load(['resource.vendor', 'farmer', 'user', 'reviewedBy', 'fulfilledBy']);
        
        return view('admin.applications.show', compact('application'));
    }

    public function approve(Request $request, ResourceApplication $application)
    {
        if ($application->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending applications can be approved.');
        }

        $validator = Validator::make($request->all(), [
            'quantity_approved' => 'required|integer|min:1|max:' . $application->quantity_requested,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $quantityApproved = $request->quantity_approved;
            $totalAmount = $quantityApproved * $application->unit_price;

            $application->update([
                'quantity_approved' => $quantityApproved,
                'total_amount' => $totalAmount,
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            return redirect()->route('admin.applications.index')
                ->with('success', 'Application approved successfully. Farmer has been notified to make payment.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error approving application: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, ResourceApplication $application)
    {
        if ($application->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending applications can be rejected.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $application->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()->route('admin.applications.index')
                ->with('success', 'Application rejected. Farmer has been notified.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error rejecting application: ' . $e->getMessage());
        }
    }

    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:resource_applications,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $count = 0;
            foreach ($request->application_ids as $id) {
                $application = ResourceApplication::find($id);
                
                if ($application && $application->status === 'pending') {
                    $totalAmount = $application->quantity_requested * $application->unit_price;
                    
                    $application->update([
                        'quantity_approved' => $application->quantity_requested,
                        'total_amount' => $totalAmount,
                        'status' => 'approved',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                    ]);
                    
                    $count++;
                }
            }

            return redirect()->route('admin.applications.index')
                ->with('success', "{$count} application(s) approved successfully.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error processing bulk approval: ' . $e->getMessage());
        }
    }
}