<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FarmerResourceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        if (!$farmer) {
            return redirect()->route('farmer.dashboard')
                ->with('error', 'Farmer profile not found.');
        }

        // Get active resources available for application
        $resources = Resource::with('vendor:id,legal_name')
            ->availableForApplication()
            ->latest()
            ->get();

        // Get farmer's applications
        $myApplications = ResourceApplication::with(['resource.vendor', 'reviewedBy'])
            ->where('farmer_id', $farmer->id)
            ->latest()
            ->get();

        return view('farmer.resources.index', compact('resources', 'myApplications', 'farmer'));
    }

    public function show(Resource $resource)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        if (!$farmer) {
            return redirect()->route('farmer.dashboard')
                ->with('error', 'Farmer profile not found.');
        }

        // Check if farmer already has an application for this resource
        $existingApplication = ResourceApplication::where('resource_id', $resource->id)
            ->where('farmer_id', $farmer->id)
            ->first();

        $resource->load('vendor');

        return view('farmer.resources.show', compact('resource', 'farmer', 'existingApplication'));
    }

    public function apply(Request $request, Resource $resource)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        if (!$farmer) {
            return redirect()->route('farmer.dashboard')
                ->with('error', 'Farmer profile not found.');
        }

        // Validate resource availability
        if (!$resource->isAvailableForApplication()) {
            return redirect()->back()
                ->with('error', 'This resource is no longer available for applications.');
        }

        // Check if farmer already has an application
        if (!$resource->canFarmerApply($farmer->id)) {
            return redirect()->back()
                ->with('error', 'You already have an active application for this resource.');
        }

        $validator = Validator::make($request->all(), [
            'quantity_requested' => 'required|integer|min:1|max:' . $resource->max_per_farmer,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            ResourceApplication::create([
                'resource_id' => $resource->id,
                'farmer_id' => $farmer->id,
                'user_id' => $user->id,
                'quantity_requested' => $request->quantity_requested,
                'unit_price' => $resource->price,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('farmer.resources.index')
                ->with('success', 'Application submitted successfully! Awaiting approval.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error submitting application: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function myApplications()
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        if (!$farmer) {
            return redirect()->route('farmer.dashboard')
                ->with('error', 'Farmer profile not found.');
        }

        $applications = ResourceApplication::with(['resource.vendor', 'reviewedBy', 'fulfilledBy'])
            ->where('farmer_id', $farmer->id)
            ->latest()
            ->get();

        return view('farmer.resources.applications', compact('applications', 'farmer'));
    }

    public function applicationDetails(ResourceApplication $application)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        // Ensure application belongs to this farmer
        if ($application->farmer_id !== $farmer->id) {
            return redirect()->route('farmer.resources.my-applications')
                ->with('error', 'Unauthorized access.');
        }

        $application->load(['resource.vendor', 'reviewedBy', 'fulfilledBy']);

        return view('farmer.resources.application-details', compact('application', 'farmer'));
    }

    public function cancelApplication(ResourceApplication $application)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        // Ensure application belongs to this farmer
        if ($application->farmer_id !== $farmer->id) {
            return redirect()->route('farmer.resources.my-applications')
                ->with('error', 'Unauthorized access.');
        }

        // Only pending applications can be cancelled
        if ($application->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending applications can be cancelled.');
        }

        try {
            $application->update(['status' => 'cancelled']);

            return redirect()->route('farmer.resources.my-applications')
                ->with('success', 'Application cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error cancelling application: ' . $e->getMessage());
        }
    }
}