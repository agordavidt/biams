<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResourceReviewController extends Controller
{
    /**
     * List vendor resource submissions for review
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Resource::with(['vendor', 'reviewedBy'])
            ->vendorResources();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $resources = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Resource::vendorResources()->count(),
            'proposed' => Resource::vendorResources()->where('status', 'proposed')->count(),
            'under_review' => Resource::vendorResources()->where('status', 'under_review')->count(),
            'approved' => Resource::vendorResources()->where('status', 'approved')->count(),
            'active' => Resource::vendorResources()->where('status', 'active')->count(),
            'rejected' => Resource::vendorResources()->where('status', 'rejected')->count(),
        ];
        
        return view('admin.resources.review.index', compact('resources', 'stats', 'status'));
    }

    /**
     * Show vendor resource for review
     */
    public function show(Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->route('admin.resources.index')
                ->with('error', 'This is not a vendor resource.');
        }

        $resource->load(['vendor', 'reviewedBy']);
        
        return view('admin.resources.review.show', compact('resource'));
    }

    /**
     * Edit vendor resource details during review
     */
    public function edit(Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->route('admin.resources.index')
                ->with('error', 'This is not a vendor resource.');
        }

        $resource->load('vendor');
        
        return view('admin.resources.review.edit', compact('resource'));
    }

    /**
     * Update vendor resource (admin modifications during review)
     */
 
    public function update(Request $request, Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->route('admin.resources.index')
                ->with('error', 'This is not a vendor resource.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:seed,fertilizer,equipment,pesticide,training,service,tractor_service,other',
            'description' => 'required|string',
            'unit' => 'nullable|string|max:50',
            'original_price' => 'required|numeric|min:0', 
            'max_per_farmer' => 'nullable|integer|min:1',
            'total_stock' => 'nullable|integer|min:1',
        ]);

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
                'original_price' => $request->original_price, 
                'max_per_farmer' => $requiresQuantity ? $request->max_per_farmer : null,
                'total_stock' => $requiresQuantity ? $request->total_stock : null,
                'available_stock' => $requiresQuantity ? $request->total_stock : null,
            ]);

            return redirect()->route('admin.resources.review.show', $resource)
                ->with('success', 'Resource details updated successfully.');

        } catch (\Exception $e) {
            Log::error('Resource update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error updating resource: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mark resource as under review
     */
    public function markUnderReview(Request $request, Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->back()
                ->with('error', 'This is not a vendor resource.');
        }

        try {
            $resource->markUnderReview();

            return redirect()->back()
                ->with('success', 'Resource marked as under review.');

        } catch (\Exception $e) {
            Log::error('Mark under review failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Approve vendor resource and set subsidized pricing
     */
    public function approve(Request $request, Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->back()
                ->with('error', 'This is not a vendor resource.');
        }

        $validator = Validator::make($request->all(), [
            'subsidized_price' => 'required|numeric|min:0|lte:' . $resource->original_price,
            'vendor_reimbursement' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string|max:500',
        ], [
            'subsidized_price.lte' => 'Subsidized price cannot exceed the original vendor price of ₦' . number_format($resource->original_price, 2),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $resource->update([
                'status' => 'approved',
                'subsidized_price' => $request->subsidized_price,
                'price' => $request->subsidized_price, 
                'vendor_reimbursement' => $request->vendor_reimbursement,
                'admin_notes' => $request->admin_notes,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);

            // TODO: Notify vendor of approval

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource approved successfully. You can now publish it to make it available to farmers.');

        } catch (\Exception $e) {
            Log::error('Resource approval failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error approving resource: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Reject vendor resource
     */
    public function reject(Request $request, Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->back()
                ->with('error', 'This is not a vendor resource.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500|min:10',
        ], [
            'rejection_reason.min' => 'Please provide a detailed reason (at least 10 characters).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $resource->markAsRejected($request->rejection_reason, Auth::id());

            // TODO: Notify vendor of rejection

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource rejected. Vendor has been notified.');

        } catch (\Exception $e) {
            Log::error('Resource rejection failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error rejecting resource: ' . $e->getMessage());
        }
    }

    /**
     * Publish approved resource
     */
    public function publish(Request $request, Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->back()
                ->with('error', 'This is not a vendor resource.');
        }

        if ($resource->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Only approved resources can be published.');
        }

        try {
            $resource->publish();

            // TODO: Notify vendor of publication

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource published successfully. It is now available to farmers.');

        } catch (\Exception $e) {
            Log::error('Resource publish failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error publishing resource: ' . $e->getMessage());
        }
    }

    /**
     * Unpublish active resource
     */
    public function unpublish(Request $request, Resource $resource)
    {
        if (!$resource->is_vendor_resource) {
            return redirect()->back()
                ->with('error', 'This is not a vendor resource.');
        }

        if ($resource->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Only active resources can be unpublished.');
        }

        try {
            $resource->unpublish();

            // TODO: Notify vendor of unpublish

            return redirect()->route('admin.resources.review.index')
                ->with('success', 'Resource unpublished successfully. It is no longer visible to farmers.');

        } catch (\Exception $e) {
            Log::error('Resource unpublish failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error unpublishing resource: ' . $e->getMessage());
        }
    }
}