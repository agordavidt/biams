<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserResourceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's practice types based on their registrations
        $userPractices = collect();
        
        if ($user->cropFarmers()->exists()) {
            $userPractices->push('crop-farmer');
        }
        if ($user->animalFarmers()->exists()) {
            $userPractices->push('animal-farmer');
        }
        if ($user->abattoirOperators()->exists()) {
            $userPractices->push('abattoir-operator');
        }
        if ($user->processors()->exists()) {
            $userPractices->push('processor');
        }

        // Get available resources for user's practices
        $resources = Resource::where('is_active', true)
            ->where(function($query) use ($userPractices) {
                $query->whereIn('target_practice', $userPractices)
                    ->orWhere('target_practice', 'all');
            })
            ->get();

        // Get user's existing applications
        $applications = ResourceApplication::where('user_id', $user->id)->get();

        return view('user.resources.index', compact('resources', 'applications'));
    }

    public function show(Resource $resource)
    {
        // Check if user has already applied
        $existingApplication = ResourceApplication::where('user_id', Auth::id())
            ->where('resource_id', $resource->id)
            ->first();

        return view('user.resources.show', compact('resource', 'existingApplication'));
    }

    public function apply(Resource $resource)
    {
        // Verify user hasn't already applied
        $existingApplication = ResourceApplication::where('user_id', Auth::id())
            ->where('resource_id', $resource->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this resource.');
        }

        return view('user.resources.apply', compact('resource'));
    }

        public function submit(Request $request, Resource $resource)
        {
            // Initialize validation rules array
            $validationRules = [];
            
            // Create a mapping between the field labels and their slugs
            $fieldMapping = [];
            
            // Safely process form fields
            foreach ($resource->form_fields as $field) {
                // Create a slug version of the label
                $fieldName = \Str::slug($field['label']);
                
                // Store mapping for later use
                $fieldMapping[$fieldName] = $field['label'];
                
                // Initialize base validation rule
                $rules = [];
                
                // Add required validation if the field is marked as required
                if (!empty($field['required'])) {
                    $rules[] = 'required';
                } else {
                    $rules[] = 'nullable';
                }
                
                // Add type-specific validation
                switch ($field['type']) {
                    case 'number':
                        $rules[] = 'numeric';
                        break;
                        
                    case 'file':
                        $rules[] = 'file';
                        $rules[] = 'max:2048'; // 2MB limit
                        break;
                        
                    case 'select':
                        if (!empty($field['options'])) {
                            $options = is_string($field['options']) 
                                ? array_map('trim', explode(',', $field['options']))
                                : (is_array($field['options']) ? $field['options'] : []);
                            
                            if (!empty($options)) {
                                $rules[] = 'in:' . implode(',', $options);
                            }
                        }
                        break;
                }
                
                // Combine rules for this field
                if (!empty($rules)) {
                    $validationRules[$fieldName] = implode('|', $rules);
                }
            }
        
            try {
                // Validate the request
                $validatedData = $request->validate($validationRules);
                
                // Process form data with original field labels
                $formData = [];
                
                foreach ($resource->form_fields as $field) {
                    $fieldName = \Str::slug($field['label']);
                    
                    // Skip if the field wasn't submitted or validated
                    if (!isset($validatedData[$fieldName])) {
                        continue;
                    }
                    
                    
                    if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                        $file = $request->file($fieldName);
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('resource_applications', $filename, 'public');
                        
                        
                        $formData[$field['label']] = [
                            'filename' => $filename,
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName()
                        ];
                    } else {
                      
                        $formData[$field['label']] = $validatedData[$fieldName];
                    }
                }
                
                
                $application = ResourceApplication::create([
                    'user_id' => Auth::id(),
                    'resource_id' => $resource->id,
                    'form_data' => $formData, 
                    'status' => 'pending'
                ]);
        
                return redirect()->route('user.resources.index')
                    ->with('success', 'Your application has been submitted successfully.');
                    
            } catch (\Exception $e) {
                // Log the error
                \Log::error('Resource application submission failed: ' . $e->getMessage());
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'There was a problem submitting your application. Please try again.']);
            }
        }
        public function track()
        {
            $applications = ResourceApplication::where('user_id', Auth::id())
                ->with('resource')
                ->latest()
                ->get();

            return view('user.resources.track', compact('applications'));
        }
}