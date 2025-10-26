<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ResourceReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Resource::with(['vendor:id,legal_name', 'reviewedBy:id,name'])
            ->whereNotNull('vendor_id');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $resources = $query->latest()->get();
        
        $stats = [
            'total' => Resource::whereNotNull('vendor_id')->count(),
            'proposed' => Resource::where('status', 'proposed')->count(),
            'under_review' => Resource::where('status', 'under_review')->count(),
            'approved' => Resource::where('status', 'approved')->count(),
            'active' => Resource::where('status', 'active')->count(),
            'rejected' => Resource::where('status', 'rejected')->count(),
        ];
        
        return view('admin.resources.review.index', compact('resources', 'stats', 'status'));
    }

    public function show(Resource $resource)
    {
        $resource->load(['vendor', 'reviewedBy:id,name']);
        
        return view('admin.resources.review.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        $resource->load('vendor');
        
        return view('admin.resources.review.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:seed,fertilizer,equipment,pesticide,training,tractor_service,other',
            'description' => 'required|string',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
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
                'price' => $request->price,
                'vendor_reimbursement_price' => $request->vendor_reimbursement_price,
                'max_per_farmer' => $request->max_per_farmer,
                'total_stock' => $request->total_stock,
                'available_stock' => $request->total_stock,
            ]);

            return redirect()->route('admin.resources.review.show', $resource)
                ->with('success', 'Resource details updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating resource: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function approve(Resource $resource)
    {
        try {
            $resource->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource approved successfully. You can now publish it to make it available to farmers.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error approving resource: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Resource $resource)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $resource->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource rejected. Vendor has been notified.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error rejecting resource: ' . $e->getMessage());
        }
    }

    public function publish(Resource $resource)
    {
        if ($resource->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Only approved resources can be published.');
        }

        try {
            $resource->update([
                'status' => 'active',
            ]);

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource published successfully. It is now available to farmers.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error publishing resource: ' . $e->getMessage());
        }
    }

    public function unpublish(Resource $resource)
    {
        if ($resource->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Only active resources can be unpublished.');
        }

        try {
            $resource->update([
                'status' => 'inactive',
            ]);

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource unpublished successfully. It is no longer visible to farmers.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error unpublishing resource: ' . $e->getMessage());
        }
    }

    public function markUnderReview(Resource $resource)
    {
        try {
            $resource->update([
                'status' => 'under_review',
            ]);

            return redirect()->back()
                ->with('success', 'Resource marked as under review.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
}