<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::with('partner')->get();
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        $partners = Partner::active()->get();
        return view('admin.resources.create', compact('partners'));
    }

    public function store(Request $request)
    {
        // Convert checkbox values to proper boolean
        $request->merge([
            'requires_payment' => $request->has('requires_payment'),
            'is_active' => $request->has('is_active')
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'boolean',
            'price' => 'required_if:requires_payment,true|nullable|numeric|min:0',
            'credo_merchant_id' => 'required_if:requires_payment,true|nullable|string',
            'form_fields' => 'required|json',
            'is_active' => 'boolean',
            'partner_id' => 'nullable|exists:partners,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $formFields = json_decode($request->form_fields, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid form fields format');
            }

            // Create resource with conditional values for payment fields
            $resource = Resource::create([
                'name' => $request->name,
                'description' => $request->description,
                'target_practice' => $request->target_practice,
                'requires_payment' => (bool)$request->requires_payment, 
                'price' => $request->requires_payment ? $request->price : 0,
                'credo_merchant_id' => $request->requires_payment ? $request->credo_merchant_id : null,
                'form_fields' => $formFields,
                'is_active' => $request->boolean('is_active', true),
                'partner_id' => $request->partner_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resource created successfully',
                'redirect' => route('admin.resources.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating resource: ' . $e->getMessage(),
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    public function edit(Resource $resource)
    {
        $partners = Partner::active()->get();
        return view('admin.resources.edit', compact('resource', 'partners'));
    }

    public function update(Request $request, Resource $resource)
    {
        // Convert checkbox values to proper boolean
        $request->merge([
            'requires_payment' => $request->has('requires_payment'),
            'is_active' => $request->has('is_active')
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'boolean',
            'price' => 'required_if:requires_payment,true|nullable|numeric|min:0',
            'credo_merchant_id' => 'required_if:requires_payment,true|nullable|string',
            'form_fields' => 'required|json',
            'is_active' => 'boolean',
            'partner_id' => 'nullable|exists:partners,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $formFields = json_decode($request->form_fields, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid form fields format');
            }

            $resource->update([
                'name' => $request->name,
                'description' => $request->description,
                'target_practice' => $request->target_practice,
                'requires_payment' => (bool)$request->requires_payment,
                'price' => $request->requires_payment ? $request->price : 0,
                'credo_merchant_id' => $request->requires_payment ? $request->credo_merchant_id : null,
                'form_fields' => $formFields,
                'is_active' => $request->boolean('is_active', true),
                'partner_id' => $request->partner_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resource updated successfully',
                'redirect' => route('admin.resources.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating resource: ' . $e->getMessage(),
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }

    public function destroy(Resource $resource)
    {
        try {
            $resource->delete();
            return redirect()->route('admin.resources.index')
                ->with('success', 'Resource deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting resource: ' . $e->getMessage());
        }
    }
}