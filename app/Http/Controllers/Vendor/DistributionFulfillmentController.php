<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionFulfillmentController extends Controller
{
    /**
     * FIXED: Search interface for distribution agents
     */
    public function searchInterface()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Get vendor's active resources for filter dropdown
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

        return view('vendor.distribution.search-interface', compact('vendor', 'resources'));
    }

    /**
     * REMOVED: searchResults() - now using unified searchFarmer() in ResourceController
     */

    /**
     * View assigned resources
     */
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

        return view('vendor.distribution.assigned-resources', compact('vendor', 'resources'));
    }

    /**
     * View applications for specific resource
     */
    public function resourceApplications(Resource $resource)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Verify resource belongs to this vendor
        if ($resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.distribution.resources')
                ->with('error', 'Unauthorized access.');
        }

        $applications = ResourceApplication::with(['farmer', 'user'])
            ->where('resource_id', $resource->id)
            ->whereIn('status', ['paid', 'approved'])
            ->latest()
            ->paginate(20);

        return view('vendor.distribution.resource-applications', compact('vendor', 'resource', 'applications'));
    }

    /**
     * FIXED: Quick fulfillment interface
     */
    public function fulfillInterface(ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $application->load(['resource', 'user', 'farmer', 'payment']);
        
        // Verify agent has access to this vendor's resources
        if ($application->resource->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized access');
        }

        return view('vendor.distribution.fulfill', compact('application', 'vendor'));
    }
}