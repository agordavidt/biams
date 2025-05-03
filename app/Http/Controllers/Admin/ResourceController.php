<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ResourceController extends Controller
{
    public function index()
    {
        // Only fetch active resources
        $resources = Resource::with('partner')->active()->get();
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        $partners = Partner::get();
        return view('admin.resources.create', compact('partners'));
    }

    public function store(Request $request)
    {
        // Convert checkbox values to proper boolean
        $request->merge([
            'requires_payment' => $request->has('requires_payment')
        ]);

        // Parse dates if provided
        if ($request->start_date) {
            $request->merge(['start_date' => Carbon::parse($request->start_date)]);
        }
        
        if ($request->end_date) {
            $request->merge(['end_date' => Carbon::parse($request->end_date)]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'boolean',
            'price' => 'required_if:requires_payment,true|nullable|numeric|min:0',
            'form_fields' => 'required|json',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
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

            // Create resource with updated fields
            $resource = Resource::create([
                'name' => $request->name,
                'description' => $request->description,
                'target_practice' => $request->target_practice,
                'requires_payment' => (bool)$request->requires_payment, 
                'price' => $request->requires_payment ? $request->price : 0,
                'form_fields' => $formFields,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
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
        // Prevent editing if resource is expired
        if ($resource->isExpired()) {
            return redirect()->route('admin.resources.index')
                ->with('error', 'Cannot edit an expired resource.');
        }

        $partners = Partner::active()->get();
        return view('admin.resources.edit', compact('resource', 'partners'));
    }

    public function update(Request $request, Resource $resource)
    {
        // Prevent updating if resource is expired
        if ($resource->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update an expired resource.',
                'errors' => ['general' => 'Resource is expired']
            ], 403);
        }

        // Convert checkbox values to proper boolean
        $request->merge([
            'requires_payment' => $request->has('requires_payment')
        ]);

        // Parse dates if provided
        if ($request->start_date) {
            $request->merge(['start_date' => Carbon::parse($request->start_date)]);
        }
        
        if ($request->end_date) {
            $request->merge(['end_date' => Carbon::parse($request->end_date)]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'boolean',
            'price' => 'required_if:requires_payment,true|nullable|numeric|min:0',
            'form_fields' => 'required|json',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
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
                'form_fields' => $formFields,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
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