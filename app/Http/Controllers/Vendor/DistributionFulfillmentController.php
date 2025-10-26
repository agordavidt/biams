<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionFulfillmentController extends Controller
{
    public function search()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        return view('vendor.distribution.search', compact('vendor'));
    }

    public function searchResults(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $request->validate([
            'search_term' => 'required|string|min:3',
        ]);

        $searchTerm = $request->search_term;

        // Search by NIN or Farmer ID
        $farmer = Farmer::where('nin', $searchTerm)
            ->orWhere('farmer_id', $searchTerm)
            ->first();

        if (!$farmer) {
            return redirect()->back()
                ->with('error', 'Farmer not found. Please check the NIN or Farmer ID.')
                ->withInput();
        }

        // Get applications for this vendor's resources that are paid
        $applications = ResourceApplication::with(['resource', 'user'])
            ->whereHas('resource', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->where('farmer_id', $farmer->id)
            ->where('status', 'paid')
            ->get();

        return view('vendor.distribution.farmer-details', compact('vendor', 'farmer', 'applications'));
    }

    public function markFulfilled(Request $request, ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Verify application belongs to this vendor's resources
        if ($application->resource->vendor_id !== $vendor->id) {
            return redirect()->back()
                ->with('error', 'Unauthorized access.');
        }

        // Only paid applications can be fulfilled
        if ($application->status !== 'paid') {
            return redirect()->back()
                ->with('error', 'Only paid applications can be marked as fulfilled.');
        }

        $request->validate([
            'fulfillment_notes' => 'nullable|string|max:500',
        ]);

        try {
            // CRITICAL: One-time fulfillment - once marked, cannot be undone
            $application->update([
                'status' => 'fulfilled',
                'quantity_fulfilled' => $application->quantity_paid,
                'fulfilled_by' => $user->id,
                'fulfilled_at' => now(),
                'fulfillment_notes' => $request->fulfillment_notes,
            ]);

            // Update resource stock
            $application->resource->decrement('available_stock', $application->quantity_paid);

            return redirect()->back()
                ->with('success', 'Resource marked as fulfilled successfully. This action is final and cannot be undone.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error marking fulfillment: ' . $e->getMessage());
        }
    }

    public function assignedResources()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Get all vendor's active resources with paid applications
        $resources = $vendor->resources()
            ->where('status', 'active')
            ->withCount([
                'applications as paid_count' => function($query) {
                    $query->where('status', 'paid');
                },
                'applications as fulfilled_count' => function($query) {
                    $query->where('status', 'fulfilled');
                }
            ])
            ->get();

        return view('vendor.distribution.resources', compact('vendor', 'resources'));
    }

    public function resourceApplications($resourceId)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $resource = $vendor->resources()->findOrFail($resourceId);

        $applications = ResourceApplication::with(['farmer', 'user'])
            ->where('resource_id', $resource->id)
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view('vendor.distribution.resource-applications', compact('vendor', 'resource', 'applications'));
    }
}