<?php

namespace App\Http\Controllers;



use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::all();
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }
    public function store(Request $request)
    {
       
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'required|boolean',
            'price' => 'required_if:requires_payment,true|numeric|min:0',
            'form_fields' => 'required|json',
        ], [
            'requires_payment.boolean' => 'The requires payment field must be true or false.',
            'price.required_if' => 'The price field is required when payment is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
            'form_fields.required' => 'The form fields are required.',
            'form_fields.json' => 'The form fields must be a valid JSON string.',
        ]);
    
        if ($validator->fails()) {
            \Log::error('Validation failed:', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            $formFields = json_decode($request->input('form_fields'), true);
            
           
    
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($formFields)) {
                \Log::error('JSON decode error:', ['error' => json_last_error_msg()]);
                return response()->json([
                    'success' => false,
                    'errors' => ['form_fields' => 'Invalid field structure or JSON']
                ], 422);
            }
    
            foreach ($formFields as $field) {
                if (!isset($field['label']) || !isset($field['type'])) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['form_fields' => 'Invalid field structure']
                    ], 422);
                }
            }
    
            $resource = Resource::create([
                'name' => $request->name,
                'description' => $request->description,
                'target_practice' => $request->target_practice,
                'requires_payment' => $request->boolean('requires_payment'),
                'price' => $request->requires_payment ? $request->price : 0,
                'form_fields' => $formFields, 
                'is_active' => true,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Resource created successfully',
                'redirect' => route('admin.resources.index')
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating resource:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating resource: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Resource $resource)
    {
        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'boolean',
            'price' => 'required_if:requires_payment,true|numeric|min:0',
            'form_fields' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $formFields = json_decode($request->input('form_fields'), true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($formFields)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['form_fields' => 'Invalid field structure or JSON']
                ], 422);
            }

            foreach ($formFields as $field) {
                if (!isset($field['label']) || !isset($field['type'])) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['form_fields' => 'Invalid field structure']
                    ], 422);
                }
            }

            $resource->update([
                'name' => $request->name,
                'description' => $request->description,
                'target_practice' => $request->target_practice,
                'requires_payment' => $request->boolean('requires_payment'),
                'price' => $request->requires_payment ? $request->price : 0,
                'form_fields' => $formFields, 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resource updated successfully',
                'redirect' => route('admin.resources.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating resource: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource deleted successfully.');
    }
}