<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use App\Models\Payment;
use App\Notifications\ResourceStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    /**
     * Display vendor's resources
     */
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $resources = $vendor->resources()
            ->with('reviewedBy')
            ->withCount([
                'applications as total_applications',
                'applications as pending_applications' => fn($q) => $q->where('status', 'pending'),
                'applications as approved_applications' => fn($q) => $q->where('status', 'approved'),
                'applications as payment_pending_applications' => fn($q) => $q->where('status', 'payment_pending'),
                'applications as paid_applications' => fn($q) => $q->where('status', 'paid'),
                'applications as fulfilled_applications' => fn($q) => $q->where('status', 'fulfilled'),
            ])
            ->latest()
            ->get();

        $stats = [
            'total' => $vendor->resources()->count(),
            'proposed' => $vendor->resources()->where('status', 'proposed')->count(),
            'under_review' => $vendor->resources()->where('status', 'under_review')->count(),
            'approved' => $vendor->resources()->where('status', 'approved')->count(),
            'active' => $vendor->resources()->where('status', 'active')->count(),
            'rejected' => $vendor->resources()->where('status', 'rejected')->count(),
        ];

        return view('vendor.resources.index', compact('vendor', 'resources', 'stats'));
    }


     /**
     * Show form to create resource
     */
    public function create()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        return view('vendor.resources.create', compact('vendor'));
    }

    /**
     * Store new resource proposal
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $validator = $this->validateResourceData($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $requiresQuantity = !in_array($request->type, ['service', 'training']);

            $resource = Resource::create([
                'vendor_id' => $vendor->id,
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'unit' => $requiresQuantity ? $request->unit : null,
                'requires_quantity' => $requiresQuantity,
                'max_per_farmer' => $requiresQuantity ? $request->max_per_farmer : null,
                'total_stock' => $requiresQuantity ? $request->total_stock : null,
                'available_stock' => $requiresQuantity ? $request->total_stock : null,
                'requires_payment' => true, // Vendor resources are always paid
                'original_price' => $request->price,
                'status' => 'proposed',
                'created_by' => $user->id,
            ]);

            return redirect()->route('vendor.resources.index')
                ->with('success', 'Resource proposal submitted successfully. Awaiting admin review.');

        } catch (\Exception $e) {
            Log::error('Vendor resource creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error submitting proposal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show specific resource
     */
    public function show(Resource $resource)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure resource belongs to this vendor
        if ($resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.resources.index')
                ->with('error', 'Unauthorized access.');
        }

        $resource->load('reviewedBy');

        // Get application statistics
        $applicationStats = [
            'total' => $resource->applications()->count(),
            'pending' => $resource->applications()->where('status', 'pending')->count(),
            'approved' => $resource->applications()->where('status', 'approved')->count(),
            'payment_pending' => $resource->applications()->where('status', 'payment_pending')->count(),
            'paid' => $resource->applications()->where('status', 'paid')->count(),
            'fulfilled' => $resource->applications()->where('status', 'fulfilled')->count(),
        ];

        return view('vendor.resources.show', compact('vendor', 'resource', 'applicationStats'));
    }

    /**
     * Edit resource (only if proposed or rejected)
     */
    public function edit(Resource $resource)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure resource belongs to this vendor
        if ($resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.resources.index')
                ->with('error', 'Unauthorized access.');
        }

        // Only allow editing if status is proposed or rejected
        if (!in_array($resource->status, ['proposed', 'rejected'])) {
            return redirect()->route('vendor.resources.show', $resource)
                ->with('error', 'Cannot edit resource in current status. Contact admin if changes are needed.');
        }

        return view('vendor.resources.edit', compact('vendor', 'resource'));
    }

    /**
     * Update resource proposal
     */
    public function update(Request $request, Resource $resource)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure resource belongs to this vendor
        if ($resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.resources.index')
                ->with('error', 'Unauthorized access.');
        }

        // Only allow editing if status is proposed or rejected
        if (!in_array($resource->status, ['proposed', 'rejected'])) {
            return redirect()->route('vendor.resources.show', $resource)
                ->with('error', 'Cannot edit resource in current status.');
        }

        $validator = $this->validateResourceData($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $requiresQuantity = !in_array($request->type, ['service', 'training']);

            $resource->update([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'unit' => $requiresQuantity ? $request->unit : null,
                'requires_quantity' => $requiresQuantity,
                'max_per_farmer' => $requiresQuantity ? $request->max_per_farmer : null,
                'total_stock' => $requiresQuantity ? $request->total_stock : null,
                'available_stock' => $requiresQuantity ? $request->total_stock : null,
                'original_price' => $request->price,
                'status' => 'proposed', // Reset to proposed after edit
                'rejection_reason' => null,
            ]);

            return redirect()->route('vendor.resources.index')
                ->with('success', 'Resource proposal updated and resubmitted for review.');

        } catch (\Exception $e) {
            Log::error('Vendor resource update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error updating proposal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete resource (only if proposed or rejected)
     */
    public function destroy(Resource $resource)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure resource belongs to this vendor
        if ($resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.resources.index')
                ->with('error', 'Unauthorized access.');
        }

        // Only allow deletion if status is proposed or rejected
        if (!in_array($resource->status, ['proposed', 'rejected'])) {
            return redirect()->route('vendor.resources.index')
                ->with('error', 'Cannot delete resource in current status.');
        }

        try {
            $resource->delete();

            return redirect()->route('vendor.resources.index')
                ->with('success', 'Resource proposal deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Vendor resource deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error deleting proposal: ' . $e->getMessage());
        }
    }


    /**
     * View all applications for vendor's resources with detailed farmer information
     */
    public function allApplications(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        $query = ResourceApplication::whereHas('resource', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
        ->with(['user', 'farmer', 'resource', 'reviewedBy', 'fulfilledBy', 'payment']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by resource
        if ($request->resource_id) {
            $query->where('resource_id', $request->resource_id);
        }

        // Filter by payment status
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by farmer name, phone, email, or NIN
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

        // Statistics for vendor
        $stats = ResourceApplication::whereHas('resource', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as payment_pending,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as fulfilled,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = ? THEN amount_paid ELSE 0 END) as total_revenue
        ', ['pending', 'approved', 'payment_pending', 'paid', 'fulfilled', 'rejected', 'paid'])->first();

        // Get vendor's resources for filter dropdown
        $resources = $vendor->resources()->get();

        return view('vendor.resources.all-applications', compact('applications', 'stats', 'resources', 'vendor'));
    }

    /**
     * View applications for specific resource with detailed farmer list
     */
    public function applications(Resource $resource, Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure resource belongs to this vendor
        if ($resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.resources.index')
                ->with('error', 'Unauthorized access.');
        }

        $query = $resource->applications()
            ->with(['user', 'farmer', 'reviewedBy', 'fulfilledBy', 'payment']);

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
                });
            });
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        // Application statistics for this resource
        $applicationStats = [
            'total' => $resource->applications()->count(),
            'pending' => $resource->applications()->where('status', 'pending')->count(),
            'approved' => $resource->applications()->where('status', 'approved')->count(),
            'payment_pending' => $resource->applications()->where('status', 'payment_pending')->count(),
            'paid' => $resource->applications()->where('status', 'paid')->count(),
            'fulfilled' => $resource->applications()->where('status', 'fulfilled')->count(),
            'rejected' => $resource->applications()->where('status', 'rejected')->count(),
            'total_revenue' => $resource->applications()->where('status', 'paid')->sum('amount_paid'),
        ];

        return view('vendor.resources.applications', compact('vendor', 'resource', 'applications', 'applicationStats'));
    }

    /**
     * Show detailed application view for vendor
     */
    public function showApplication(ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure application is for vendor's resource
        if ($application->resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.resources.all-applications')
                ->with('error', 'Unauthorized access.');
        }

        $application->load([
            'user', 
            'farmer', 
            'resource',
            'reviewedBy',
            'fulfilledBy',
            'payment'
        ]);

        return view('vendor.resources.application-show', compact('application', 'vendor'));
    }

    /**
     * Verify payment and approve application (Vendor's primary action)
     */
    public function verifyAndApprove(Request $request, ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure application is for vendor's resource
        if ($application->resource->vendor_id !== $vendor->id) {
            return back()->with('error', 'Unauthorized access.');
        }

        // Check if application can be approved
        if (!in_array($application->status, ['pending', 'payment_pending'])) {
            return back()->with('error', 'Application cannot be approved. Current status: ' . $application->status);
        }

        $validated = $request->validate([
            'quantity_approved' => $application->resource->requires_quantity 
                ? 'required|integer|min:1|max:' . $application->quantity_requested 
                : 'nullable',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            // For paid resources, verify payment exists
            if ($application->resource->requires_payment) {
                $payment = Payment::where('reference', $application->payment_reference)
                    ->where('resourceId', $application->resource->id)
                    ->where('customerId', $application->user_id)
                    ->where('status', 'success')
                    ->first();

                if (!$payment) {
                    DB::rollBack();
                    return back()->with('error', 'Payment not found or not verified. Please ensure payment is completed.');
                }

                // Update application with payment details
                $application->update([
                    'payment_status' => 'verified',
                    'amount_paid' => $payment->transAmount,
                    'paid_at' => $payment->transDate,
                ]);
            }

            $quantityApproved = $application->resource->requires_quantity 
                ? $validated['quantity_approved'] 
                : null;

            // Check stock availability
            if ($application->resource->requires_quantity) {
                if ($application->resource->available_stock < $quantityApproved) {
                    DB::rollBack();
                    return back()->with('error', 'Insufficient stock. Only ' . $application->resource->available_stock . ' units available.');
                }

                // Decrement stock
                $application->resource->decrementStock($quantityApproved);
            }

            // Approve application
            $application->update([
                'status' => $application->resource->requires_payment ? 'paid' : 'approved',
                'quantity_approved' => $quantityApproved,
                'quantity_paid' => $quantityApproved,
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);

            // Notify farmer
            try {
                $application->user->notify(
                    new ResourceStatusUpdated($application, 'Your application has been approved by the vendor.')
                );
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }

            // Log action
            Log::info('Vendor approved application', [
                'application_id' => $application->id,
                'vendor_id' => $vendor->id,
                'approved_by' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('vendor.resources.application.show', $application)
                ->with('success', 'Application approved successfully! Farmer has been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Vendor approval failed: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'vendor_id' => $vendor->id,
            ]);

            return back()->with('error', 'Failed to approve application: ' . $e->getMessage());
        }
    }

    /**
     * Reject application (Vendor can reject if issues found)
     */
    public function rejectApplication(Request $request, ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure application is for vendor's resource
        if ($application->resource->vendor_id !== $vendor->id) {
            return back()->with('error', 'Unauthorized access.');
        }

        if (!in_array($application->status, ['pending', 'payment_pending'])) {
            return back()->with('error', 'Application cannot be rejected at this stage.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500'
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
            'rejection_reason.min' => 'Rejection reason must be at least 10 characters.'
        ]);

        try {
            $application->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            // Notify farmer
            try {
                $application->user->notify(
                    new ResourceStatusUpdated($application, $validated['rejection_reason'])
                );
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }

            Log::info('Vendor rejected application', [
                'application_id' => $application->id,
                'vendor_id' => $vendor->id,
            ]);

            return redirect()->route('vendor.resources.application.show', $application)
                ->with('success', 'Application rejected. Farmer has been notified.');

        } catch (\Exception $e) {
            Log::error('Vendor rejection failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to reject application: ' . $e->getMessage());
        }
    }

    /**
     * Mark application as fulfilled after resource delivery
     */
    public function fulfillApplication(Request $request, ResourceApplication $application)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure application is for vendor's resource
        if ($application->resource->vendor_id !== $vendor->id) {
            return back()->with('error', 'Unauthorized access.');
        }

        // Can only fulfill paid/approved applications
        if (!in_array($application->status, ['paid', 'approved'])) {
            return back()->with('error', 'Application cannot be fulfilled. Current status: ' . $application->status);
        }

        $validated = $request->validate([
            'quantity_fulfilled' => $application->resource->requires_quantity 
                ? 'required|integer|min:1|max:' . ($application->quantity_paid ?? $application->quantity_approved)
                : 'nullable',
            'fulfillment_notes' => 'nullable|string|max:500',
        ]);

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

            Log::info('Vendor fulfilled application', [
                'application_id' => $application->id,
                'vendor_id' => $vendor->id,
                'quantity_fulfilled' => $quantityFulfilled,
            ]);

            return redirect()->route('vendor.resources.application.show', $application)
                ->with('success', 'Application marked as fulfilled! Farmer has been notified.');

        } catch (\Exception $e) {
            Log::error('Fulfillment failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to mark as fulfilled: ' . $e->getMessage());
        }
    }

    /**
     * Search farmer by details (for quick lookup at distribution point)
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
            $query = ResourceApplication::whereHas('resource', function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
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
            Log::error('Farmer search failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Search failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Dashboard - vendor statistics
     */
    public function dashboard()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Resource statistics
        $resourceStats = [
            'total' => $vendor->resources()->count(),
            'active' => $vendor->resources()->where('status', 'active')->count(),
            'pending_review' => $vendor->resources()->whereIn('status', ['proposed', 'under_review'])->count(),
            'approved' => $vendor->resources()->where('status', 'approved')->count(),
            'rejected' => $vendor->resources()->where('status', 'rejected')->count(),
        ];

        // Application statistics with payment info
        $applicationStats = ResourceApplication::whereHas('resource', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_verification,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as fulfilled,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status IN (?, ?) THEN amount_paid ELSE 0 END) as total_revenue
        ', ['payment_pending', 'paid', 'fulfilled', 'rejected', 'paid', 'fulfilled'])->first();

        // Recent applications needing attention
        $pendingApplications = ResourceApplication::whereHas('resource', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
        ->whereIn('status', ['pending', 'payment_pending'])
        ->with(['user', 'farmer', 'resource'])
        ->latest()
        ->limit(10)
        ->get();

        // Applications ready for fulfillment
        $readyForFulfillment = ResourceApplication::whereHas('resource', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
        ->whereIn('status', ['paid', 'approved'])
        ->with(['user', 'farmer', 'resource'])
        ->latest()
        ->limit(10)
        ->get();

        // Top performing resources
        $topResources = $vendor->resources()
            ->withCount('applications')
            ->where('status', 'active')
            ->orderByDesc('applications_count')
            ->limit(5)
            ->get();

        return view('vendor.dashboard', compact(
            'vendor', 
            'resourceStats', 
            'applicationStats', 
            'pendingApplications',
            'readyForFulfillment',
            'topResources'
        ));
    }


    // ... keep existing methods (create, store, show, edit, update, destroy, validateResourceData)
    /**
     * Validation rules for resource data
     */
    protected function validateResourceData(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:seed,fertilizer,equipment,pesticide,training,service,tractor_service,other',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:0',
        ];

        // Only require quantity fields for physical resources
        if (!in_array($request->type, ['service', 'training'])) {
            $rules['unit'] = 'required|string|max:50';
            $rules['max_per_farmer'] = 'required|integer|min:1';
            $rules['total_stock'] = 'required|integer|min:1';
        }

        return Validator::make($request->all(), $rules);
    }
}
