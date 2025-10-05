<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    /**
     * Display all active resources available to farmers
     */
    public function index()
    {
        $resources = Resource::with('partner')
            ->active()
            ->latest()
            ->paginate(12);

        $userApplications = ResourceApplication::where('user_id', Auth::id())
            ->pluck('resource_id')
            ->toArray();

        return view('farmer.resources.index', compact('resources', 'userApplications'));
    }

    /**
     * Show a specific resource
     */
    public function show(Resource $resource)
    {
        // Check if resource is active
        if (!$resource->isActive()) {
            return redirect()->route('farmer.resources.index')
                ->with('error', 'This resource is no longer available.');
        }

        $resource->load('partner');
        
        // Check if user has already applied
        $existingApplication = ResourceApplication::where('user_id', Auth::id())
            ->where('resource_id', $resource->id)
            ->first();

        return view('farmer.resources.show', compact('resource', 'existingApplication'));
    }

    /**
     * Apply for a resource
     */
    public function apply(Request $request, Resource $resource)
    {
        // Check if resource is active
        if (!$resource->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'This resource is no longer available.'
            ], 403);
        }

        // Check if user has already applied
        $existingApplication = ResourceApplication::where('user_id', Auth::id())
            ->where('resource_id', $resource->id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this resource.'
            ], 422);
        }

        // Validate form data against resource's form_fields
        $validator = Validator::make($request->all(), [
            'form_data' => 'required|array',
            'payment_reference' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // If resource requires payment, validate payment reference
            if ($resource->requires_payment) {
                if (empty($request->payment_reference)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment is required for this resource.'
                    ], 422);
                }

                
                $paymentStatus = 'pending';
            } else {
                $paymentStatus = null;
            }

            // Create application
            $application = ResourceApplication::create([
                'user_id' => Auth::id(),
                'resource_id' => $resource->id,
                'form_data' => $request->form_data,
                'payment_reference' => $request->payment_reference,
                'status' => 'pending',
                'payment_status' => $paymentStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your application has been submitted successfully.',
                'redirect' => route('farmer.resources.applications.show', $application->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View user's applications
     */
    public function applications(Request $request)
    {
        $query = ResourceApplication::with(['resource', 'resource.partner'])
            ->where('user_id', Auth::id());

        // Filter by status if provided
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        return view('farmer.resources.applications', compact('applications'));
    }

    /**
     * View a specific application
     */
    public function showApplication(ResourceApplication $application)
    {
        // Ensure user owns this application
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->load(['resource', 'resource.partner']);

        return view('farmer.resources.application-show', compact('application'));
    }

}