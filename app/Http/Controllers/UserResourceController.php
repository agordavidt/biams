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
        $userPractices = $this->getUserPractices($user);
        $resources = Resource::where('is_active', true)
            ->where(function ($query) use ($userPractices) {
                $query->whereIn('target_practice', $userPractices)
                    ->orWhere('target_practice', 'all');
            })->get();
        $applications = ResourceApplication::where('user_id', $user->id)->get();

        return view('user.resources.index', compact('resources', 'applications'));
    }

    public function show(Resource $resource)
    {
        $existingApplication = ResourceApplication::where('user_id', Auth::id())
            ->where('resource_id', $resource->id)
            ->first();
        return view('user.resources.show', compact('resource', 'existingApplication'));
    }

    public function apply(Resource $resource)
    {
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
        \Log::info('Submit request received', ['request' => $request->all()]);

        $validationRules = $this->getValidationRules($resource);
        if ($resource->requires_payment && $resource->payment_option === 'bank_transfer') {
            $validationRules['payment_receipt'] = 'required|file|mimes:jpg,png,pdf|max:2048';
        }

        try {
            $validated = $request->validate($validationRules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::info('Validation failed', ['errors' => $e->errors()]);
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            throw $e; // For non-AJAX, redirect back with errors
        }

        $formData = [];
        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $path = $file->store('resource_applications', 'public');
                $formData[$field['label']] = $path;
            } else {
                $formData[$field['label']] = $validated[$fieldName] ?? null;
            }
        }

        $applicationData = [
            'user_id' => Auth::id(),
            'resource_id' => $resource->id,
            'form_data' => $formData,
            'status' => 'pending',
        ];

        if ($resource->requires_payment) {
            $applicationData['payment_status'] = 'pending';
            if ($resource->payment_option === 'bank_transfer' && $request->hasFile('payment_receipt')) {
                $receipt = $request->file('payment_receipt');
                $path = $receipt->store('payment_receipts', 'public');
                $applicationData['payment_receipt_path'] = $path;
            }
        }

        ResourceApplication::create($applicationData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Application submitted successfully']);
        }
        return redirect()->route('user.resources.index')
            ->with('success', 'Application submitted successfully.');
    }
    public function track()
    {
        $applications = ResourceApplication::where('user_id', Auth::id())
            ->with('resource')
            ->latest()
            ->get();
        return view('user.resources.track', compact('applications'));
    }

    private function getUserPractices($user): array
    {
        $practices = [];
        if ($user->cropFarmers()->exists()) $practices[] = 'crop-farmer';
        if ($user->animalFarmers()->exists()) $practices[] = 'animal-farmer';
        if ($user->abattoirOperators()->exists()) $practices[] = 'abattoir-operator';
        if ($user->processors()->exists()) $practices[] = 'processor';
        return $practices;
    }

    private function getValidationRules(Resource $resource): array
    {
        $rules = [];
        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            $fieldRules = $field['required'] ? 'required' : 'nullable';
            switch ($field['type']) {
                case 'number':
                    $fieldRules .= '|numeric';
                    break;
                case 'file':
                    $fieldRules .= '|file|max:2048';
                    break;
                case 'select':
                    if (!empty($field['options'])) {
                        $options = is_array($field['options']) ? $field['options'] : explode(',', $field['options']);
                        $fieldRules .= '|in:' . implode(',', $options);
                    }
                    break;
            }
            $rules[$fieldName] = $fieldRules;
        }
        return $rules;
    }
}