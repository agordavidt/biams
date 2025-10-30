<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DistributionDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('login')
                ->with('error', 'No vendor account found.');
        }

        // Check if agent has specific resource assignments
        $hasAssignments = $user->hasResourceAssignments();

        // DEBUG: Log assignment status
        Log::info('Distribution Dashboard Debug', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'vendor_id' => $vendor->id,
            'has_assignments' => $hasAssignments,
        ]);

        // Calculate statistics based on assignment status
        if ($hasAssignments) {
            // Agent has specific assignments - count only assigned resources
            $assignedResources = $user->assignedResources()
                ->where('vendor_id', $vendor->id)
                ->where('status', 'active')
                ->get();

            $assignedResourceCount = $assignedResources->count();

            // DEBUG: Log assigned resources
            Log::info('Agent has assignments', [
                'assigned_count' => $assignedResourceCount,
                'resource_ids' => $assignedResources->pluck('id')->toArray(),
                'resource_names' => $assignedResources->pluck('name')->toArray(),
            ]);

            // Get resource IDs that agent can access
            $accessibleResourceIds = $assignedResources->pluck('id');

            // Count fulfillments for assigned resources only
            $fulfilledToday = ResourceApplication::whereIn('resource_id', $accessibleResourceIds)
                ->where('status', 'fulfilled')
                ->where('fulfilled_by', $user->id)
                ->whereDate('fulfilled_at', Carbon::today())
                ->count();

            // Count pending fulfillments for assigned resources only
            $pendingFulfillments = ResourceApplication::whereIn('resource_id', $accessibleResourceIds)
                ->whereIn('status', ['paid', 'approved'])
                ->count();

        } else {
            // Agent has full access - count all vendor resources
            $allResources = $vendor->resources()
                ->where('status', 'active')
                ->get();

            $assignedResourceCount = $allResources->count();

            // DEBUG: Log all resources
            Log::info('Agent has full access', [
                'total_count' => $assignedResourceCount,
                'resource_ids' => $allResources->pluck('id')->toArray(),
            ]);

            // Get all vendor resource IDs
            $accessibleResourceIds = $allResources->pluck('id');

            // Count all fulfillments by this agent
            $fulfilledToday = ResourceApplication::whereIn('resource_id', $accessibleResourceIds)
                ->where('status', 'fulfilled')
                ->where('fulfilled_by', $user->id)
                ->whereDate('fulfilled_at', Carbon::today())
                ->count();

            // Count all pending fulfillments
            $pendingFulfillments = ResourceApplication::whereIn('resource_id', $accessibleResourceIds)
                ->whereIn('status', ['paid', 'approved'])
                ->count();
        }

        $stats = [
            'assigned_resources' => $assignedResourceCount,
            'fulfilled_today' => $fulfilledToday,
            'pending_fulfillments' => $pendingFulfillments,
        ];

        // DEBUG: Log final stats
        Log::info('Dashboard Stats', $stats);

        return view('vendor.distribution.dashboard', compact('vendor', 'stats'));
    }
}