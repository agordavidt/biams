<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionFulfillmentController extends Controller
{
    /**
     * UPDATED: Now shows only assigned resources OR all if no assignments
     */
    public function assignedResources()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Check if this agent has specific resource assignments
        $hasAssignments = $user->assignedResources()->exists();

        if ($hasAssignments) {
            // Show only assigned resources
            $resources = $user->assignedResources()
                ->where('vendor_id', $vendor->id)
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
        } else {
            // No assignments = show all vendor resources (backward compatible)
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
        }

        return view('vendor.distribution.assigned-resources', compact('vendor', 'resources', 'hasAssignments'));
    }

    /**
     * UPDATED: Verify agent has access to resource applications
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

        // Check if agent has access to this resource
        $hasAssignments = $user->assignedResources()->exists();
        
        if ($hasAssignments && !$user->isAssignedToResource($resource->id)) {
            return redirect()->route('vendor.distribution.resources')
                ->with('error', 'You are not assigned to this resource.');
        }

        $applications = ResourceApplication::with(['farmer', 'user'])
            ->where('resource_id', $resource->id)
            ->whereIn('status', ['paid', 'approved'])
            ->latest()
            ->paginate(20);

        return view('vendor.distribution.resource-applications', compact('vendor', 'resource', 'applications'));
    }

    /**
     * UPDATED: Verify agent can fulfill this application
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
        
        // Verify resource belongs to vendor
        if ($application->resource->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized access to this vendor\'s resources');
        }

        // Check if agent has access to this resource
        $hasAssignments = $user->assignedResources()->exists();
        
        if ($hasAssignments && !$user->isAssignedToResource($application->resource_id)) {
            abort(403, 'You are not assigned to fulfill this resource');
        }

        return view('vendor.distribution.fulfill', compact('application', 'vendor'));
    }

    /**
     * UPDATED: Search interface with resource filtering based on assignments
     */
    public function searchInterface()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Check if agent has specific assignments
        $hasAssignments = $user->assignedResources()->exists();

        if ($hasAssignments) {
            // Show only assigned resources in filter
            $resources = $user->assignedResources()
                ->where('vendor_id', $vendor->id)
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
        } else {
            // Show all vendor resources
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
        }

        return view('vendor.distribution.search-interface', compact('vendor', 'resources', 'hasAssignments'));
    }
}