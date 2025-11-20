<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use App\Notifications\ResourceStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DistributionFulfillmentController extends Controller
{
    /**
     * Show only assigned resources OR all if no assignments
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
     * View applications for specific resource - Distributor version
     */
    public function resourceApplications(Resource $resource, Request $request)
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

        // Get applications ready for fulfillment
        $query = $resource->applications()
            ->with(['user', 'farmer', 'reviewedBy', 'fulfilledBy', 'payment'])
            ->whereIn('status', ['paid', 'approved', 'fulfilled']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search farmers
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
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

        $applications = $query->latest()->paginate(20)->withQueryString();

        // Application statistics for this resource
        $applicationStats = [
            'total' => $resource->applications()->count(),
            'paid' => $resource->applications()->where('status', 'paid')->count(),
            'approved' => $resource->applications()->where('status', 'approved')->count(),
            'fulfilled' => $resource->applications()->where('status', 'fulfilled')->count(),
        ];

        return view('vendor.distribution.resource-applications', compact('vendor', 'resource', 'applications', 'applicationStats'));
    }

    /**
     * Search interface for distributors
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

    /**
     * Search farmer by details for fulfillment
     */
    public function searchFarmer(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json(['error' => 'No vendor account found'], 403);
        }

        $request->validate([
            'search' => 'required|string|min:3',
            'resource_id' => 'nullable|exists:resources,id'
        ]);

        $search = $request->search;
        $resourceId = $request->resource_id;

        try {
            // Check if agent has assignments
            $hasAssignments = $user->assignedResources()->exists();
            
            $query = ResourceApplication::whereHas('resource', function($q) use ($vendor, $user, $hasAssignments) {
                $q->where('vendor_id', $vendor->id);
                
                // If agent has assignments, filter by assigned resources only
                if ($hasAssignments) {
                    $q->whereIn('id', $user->assignedResources()->pluck('resources.id'));
                }
            })
            ->whereIn('status', ['paid', 'approved'])
            ->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                })
                ->orWhereHas('farmer', function($farmerQuery) use ($search) {
                    $farmerQuery->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('phone_number', 'like', "%$search%")
                        ->orWhere('nin', 'like', "%$search%");
                })
                ->orWhere('payment_reference', 'like', "%$search%");
            });

            if ($resourceId) {
                // Verify agent has access to this resource if assignments exist
                if ($hasAssignments && !$user->isAssignedToResource($resourceId)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'You are not assigned to this resource.'
                    ], 403);
                }
                
                $query->where('resource_id', $resourceId);
            }

            $applications = $query->with(['user', 'farmer', 'resource', 'payment'])->get();

            return response()->json([
                'success' => true,
                'count' => $applications->count(),
                'applications' => $applications->map(function($app) {
                    return [
                        'id' => $app->id,
                        'resource_name' => $app->resource->name,
                        'farmer_name' => $app->farmer ? $app->farmer->full_name : $app->user->name,
                        'phone' => $app->farmer ? $app->farmer->phone_number : ($app->user->phone ?? 'N/A'),
                        'email' => $app->user->email,
                        'nin' => $app->farmer ? $app->farmer->nin : 'N/A',
                        'quantity_approved' => $app->quantity_approved ?? 1,
                        'quantity_fulfilled' => $app->quantity_fulfilled ?? 0,
                        'amount_paid' => $app->amount_paid ?? 0,
                        'payment_reference' => $app->payment_reference,
                        'status' => $app->status,
                        'is_fulfilled' => $app->status === 'fulfilled',
                        'applied_at' => $app->created_at->format('Y-m-d H:i'),
                        'paid_at' => $app->paid_at ? $app->paid_at->format('Y-m-d H:i') : null,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Distributor farmer search failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Search failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Show application details for distributor fulfillment
     */
    public function showApplication(ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Ensure application is for vendor's resource
        if ($application->resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.distribution.resources')
                ->with('error', 'Unauthorized access.');
        }

        // Check if agent has access to this resource
        $hasAssignments = $user->assignedResources()->exists();
        
        if ($hasAssignments && !$user->isAssignedToResource($application->resource_id)) {
            return redirect()->route('vendor.distribution.resources')
                ->with('error', 'You are not assigned to this resource.');
        }

        $application->load([
            'user', 
            'farmer', 
            'resource',
            'reviewedBy',
            'fulfilledBy',
            'payment'
        ]);

        return view('vendor.distribution.application-show', compact('application', 'vendor'));
    }

    /**
     * Mark application as fulfilled - Distributor action
     */
    public function fulfillApplication(Request $request, ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure application is for vendor's resource
        if ($application->resource->vendor_id !== $vendor->id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized access.'], 403);
            }
            return back()->with('error', 'Unauthorized access.');
        }

        // Check if agent has access to this resource
        $hasAssignments = $user->assignedResources()->exists();
        
        if ($hasAssignments && !$user->isAssignedToResource($application->resource_id)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'You are not assigned to this resource.'], 403);
            }
            return back()->with('error', 'You are not assigned to this resource.');
        }

        // Can only fulfill paid/approved applications
        if (!in_array($application->status, ['paid', 'approved'])) {
            $message = 'Application cannot be fulfilled. Current status: ' . $application->status;
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => $message], 400);
            }
            return back()->with('error', $message);
        }

        $validated = $request->validate([
            'quantity_fulfilled' => $application->resource->requires_quantity 
                ? 'required|integer|min:1|max:' . ($application->quantity_paid ?? $application->quantity_approved)
                : 'nullable',
            'fulfillment_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $quantityFulfilled = $application->resource->requires_quantity
                ? $validated['quantity_fulfilled']
                : null;

            $application->update([
                'status' => 'fulfilled',
                'quantity_fulfilled' => $quantityFulfilled,
                'fulfilled_by' => $user->id,
                'fulfilled_at' => now(),
                'fulfillment_notes' => $validated['fulfillment_notes'] ?? null,
            ]);

            // Notify farmer
            try {
                $application->user->notify(
                    new ResourceStatusUpdated($application, 'Your resource has been delivered.')
                );
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }

            Log::info('Distributor fulfilled application', [
                'application_id' => $application->id,
                'vendor_id' => $vendor->id,
                'distributor_id' => $user->id,
                'quantity_fulfilled' => $quantityFulfilled,
            ]);

            DB::commit();

            // Return JSON for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Application marked as fulfilled! Farmer has been notified.',
                    'application' => [
                        'id' => $application->id,
                        'status' => $application->status,
                        'fulfilled_at' => $application->fulfilled_at->format('Y-m-d H:i:s'),
                    ]
                ]);
            }

            // Redirect to distributor's application show page (NOT vendor's page)
            return redirect()->route('vendor.distribution.application.show', $application->id)
                ->with('success', 'Application marked as fulfilled! Farmer has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Distributor fulfillment failed: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to mark as fulfilled: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to mark as fulfilled: ' . $e->getMessage());
        }
    }

    /**
     * Quick fulfillment interface (optional - for simplified view)
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

}