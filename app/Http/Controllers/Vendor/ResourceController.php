<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'No vendor account found.');
        }

        $resources = $vendor->resources()
            ->with('reviewedBy:id,name')
            ->latest()
            ->get();

        return view('vendor.resources.index', compact('vendor', 'resources'));
    }

    public function create()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'No vendor account found.');
        }

        return view('vendor.resources.create', compact('vendor'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'No vendor account found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:seed,fertilizer,equipment,pesticide,training,tractor_service,other',
            'description' => 'required|string',
            'unit' => 'required|string|max:50',
            'co_payment_price' => 'required|numeric|min:0',
            'vendor_reimbursement_price' => 'required|numeric|min:0',
            'max_per_farmer' => 'required|integer|min:1',
            'total_stock' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $resource = Resource::create([
                'vendor_id' => $vendor->id,
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'unit' => $request->unit,
                'price' => $request->co_payment_price, // Farmer co-payment
                'vendor_reimbursement_price' => $request->vendor_reimbursement_price,
                'max_per_farmer' => $request->max_per_farmer,
                'total_stock' => $request->total_stock,
                'available_stock' => $request->total_stock,
                'status' => 'proposed',
            ]);

            return redirect()->route('vendor.resources.index')
                ->with('success', 'Resource proposal submitted successfully. Awaiting State Admin review.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error submitting proposal: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Resource $resource)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure resource belongs to this vendor
        if ($resource->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.resources.index')
                ->with('error', 'Unauthorized access.');
        }

        $resource->load('reviewedBy:id,name');

        return view('vendor.resources.show', compact('vendor', 'resource'));
    }

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
                ->with('error', 'Cannot edit resource in current status.');
        }

        return view('vendor.resources.edit', compact('vendor', 'resource'));
    }

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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:seed,fertilizer,equipment,pesticide,training,tractor_service,other',
            'description' => 'required|string',
            'unit' => 'required|string|max:50',
            'co_payment_price' => 'required|numeric|min:0',
            'vendor_reimbursement_price' => 'required|numeric|min:0',
            'max_per_farmer' => 'required|integer|min:1',
            'total_stock' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $resource->update([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'unit' => $request->unit,
                'price' => $request->co_payment_price,
                'vendor_reimbursement_price' => $request->vendor_reimbursement_price,
                'max_per_farmer' => $request->max_per_farmer,
                'total_stock' => $request->total_stock,
                'available_stock' => $request->total_stock,
                'status' => 'proposed', // Reset to proposed after edit
                'rejection_reason' => null,
            ]);

            return redirect()->route('vendor.resources.index')
                ->with('success', 'Resource proposal updated and resubmitted for review.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating proposal: ' . $e->getMessage())
                ->withInput();
        }
    }

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
            return redirect()->back()
                ->with('error', 'Error deleting proposal: ' . $e->getMessage());
        }
    }
}