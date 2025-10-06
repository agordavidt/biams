<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        return view('user.resources.index', compact('resources', 'userApplications'));
    }

    /**
     * Show a specific resource
     */
    public function show(Resource $resource)
    {
        if (!$resource->isActive()) {
            return redirect()->route('farmer.resources.index')
                ->with('error', 'This resource is no longer available.');
        }

        $user = Auth::user();
        $resource->load('partner');
        
        $existingApplication = ResourceApplication::where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->first();

        $hasPaid = $this->hasUserPaidForResource($user->id, $resource->id);

        return view('user.resources.show', compact('resource', 'existingApplication', 'hasPaid'));
    }

    /**
     * Show application form for a resource
     */
    public function apply(Resource $resource)
    {
        $user = Auth::user();
        
        // Check if user has already applied
        if ($resource->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->route('farmer.resources.track')
                ->with('error', 'You have already applied for this resource.');
        }

        // Check payment status
        $hasPaid = $this->hasUserPaidForResource($user->id, $resource->id);
        
        // If requires payment and hasn't paid, show payment required message
        if ($resource->requires_payment && !$hasPaid) {
            return view('user.resources.apply', compact('resource', 'hasPaid'))
                ->with('info', 'Please complete payment before submitting your application.');
        }

        return view('user.resources.apply', compact('resource', 'hasPaid'));
    }

    /**
     * Initiate payment for a resource
     */
    public function initiatePayment(Request $request, Resource $resource)
    {
        $user = Auth::user();
        
        // Check if already applied
        if ($resource->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->route('farmer.resources.track')
                ->with('error', 'You have already applied for this resource.');
        }

        // Check if already paid
        if ($this->hasUserPaidForResource($user->id, $resource->id)) {
            return redirect()->route('farmer.resources.apply', $resource)
                ->with('info', 'Payment already completed. Please submit your application.');
        }

        $reference = 'RES-' . $user->id . '-' . $resource->id . '-' . time();
        
        // Process form data, handling file uploads
        $formData = [];
        $filePaths = [];

        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            
            if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $path = $file->store('temp/resource-applications', 'public');
                $filePaths[$fieldName] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName()
                ];
                $formData[$fieldName] = $path;
            } else {
                $formData[$fieldName] = $request->input($fieldName);
            }
        }

        try {
            $apiKey = env('CREDO_PUBLIC_KEY');
            
            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->accept('application/json')
                ->withHeaders([
                    'authorization' => $apiKey,
                    'content_type' => 'application/json',
                ])
                ->post(env('CREDO_URL') . '/transaction/initialize', [
                    'email' => $user->email,
                    'metadata' => [
                        'resource_id' => $resource->id,
                        'user_id' => $user->id,
                    ],
                    'amount' => ($resource->price * 100),
                    'reference' => $reference,
                    'callbackUrl' => route('farmer.payment.callback'),
                    'bearer' => 0,
                ]);
            
            $responseData = $response->collect('data');
            
            if (isset($responseData['authorizationUrl'])) {
                // Store form data in session
                session()->put('resource_application.' . $reference, [
                    'resource_id' => $resource->id,
                    'form_data' => $formData,
                    'file_paths' => $filePaths,
                ]);
                
                return redirect($responseData['authorizationUrl']);
            }
            
            // Clean up temporary files if payment initialization fails
            foreach ($filePaths as $fileData) {
                Storage::disk('public')->delete($fileData['path']);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment gateway took too long to respond. Please try again.');
            
        } catch (\Exception $e) {
            // Clean up temporary files on error
            foreach ($filePaths as $fileData) {
                Storage::disk('public')->delete($fileData['path']);
            }
            
            Log::error('Error initializing payment: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'reference' => $reference,
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to process payment at this time. Please try again later.');
        }
    }

    /**
     * Handle payment callback from Credo
     */
    public function handlePaymentCallback(Request $request)
    {
        try {
            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->accept('application/json')
                ->withHeaders([
                    'authorization' => env('CREDO_SECRET_KEY'),
                    'content-type' => 'application/json',
                ])
                ->get(env('CREDO_URL') . "/transaction/{$request->reference}/verify");
            
            if (!$response->successful()) {
                return redirect()->route('farmer.resources.index')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }
            
            $paymentData = $response->json('data');
            $status = $paymentData['status'] ?? 'failed';
            $message = $paymentData['statusMessage'] ?? 'Payment failed';
            
            if ($status === 'success' || $message === 'Successfully processed') {
                // Get the form data from session
                $sessionData = session()->get('resource_application.' . $request->reference);
                
                if (!$sessionData) {
                    return redirect()->route('farmer.resources.index')
                        ->with('error', 'Application data not found. Please try again.');
                }
                
                $resource = Resource::findOrFail($sessionData['resource_id']);
                $user = Auth::user();
                
                // Log payment to payments table
                Payment::create([
                    'businessName' => 'BIAMS',
                    'reference' => $request->reference,
                    'transAmount' => $resource->price,
                    'transFee' => 0,
                    'transTotal' => $resource->price,
                    'transDate' => now(),
                    'settlementAmount' => $resource->price,
                    'status' => 'success',
                    'statusMessage' => $message,
                    'customerId' => $user->id,
                    'resourceId' => $resource->id,
                    'resourceOwnerId' => $resource->partner_id ?? 1,
                    'channelId' => 'WEB',
                    'currencyCode' => 'NGN'
                ]);
                
                // Create the application immediately after successful payment
                $this->createApplicationFromPayment($sessionData, $user->id, $request->reference);
                
                // Clean up session data
                session()->forget('resource_application.' . $request->reference);
                
                return redirect()->route('farmer.resources.track')
                    ->with('success', 'Payment successful! Your application has been submitted.');
            } else {
                // Clean up temporary files on failed payment
                $sessionData = session()->get('resource_application.' . $request->reference);
                if ($sessionData && isset($sessionData['file_paths'])) {
                    foreach ($sessionData['file_paths'] as $fileData) {
                        Storage::disk('public')->delete($fileData['path']);
                    }
                }
                session()->forget('resource_application.' . $request->reference);
                
                return redirect()->route('farmer.resources.index')
                    ->with('error', 'Payment failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('farmer.resources.index')
                ->with('error', 'Error processing payment callback. Please contact support.');
        }
    }

    /**
     * Submit resource application (for free resources or after payment)
     */
    public function submit(Request $request, Resource $resource)
    {
        $user = Auth::user();
        
        // Check if already applied
        if ($resource->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->route('farmer.resources.track')
                ->with('error', 'You have already applied for this resource.');
        }
        
        // If requires payment, verify payment exists
        if ($resource->requires_payment) {
            $payment = Payment::where('customerId', $user->id)
                ->where('resourceId', $resource->id)
                ->where('status', 'success')
                ->first();
                
            if (!$payment) {
                return redirect()->back()->with('error', 'Payment required. Please complete payment first.');
            }
            
            // Check if this payment was already used
            $existingApp = ResourceApplication::where('payment_reference', $payment->reference)->first();
            if ($existingApp) {
                return redirect()->route('farmer.resources.track')
                    ->with('info', 'Application already submitted with this payment.');
            }
        }
        
        // Validate the application data
        $this->validateApplication($request, $resource);

        try {
            // Process form data
            $formData = $this->processFormData($request, $resource);
            
            // Create the application
            $application = ResourceApplication::create([
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'form_data' => $formData,
                'payment_reference' => $resource->requires_payment ? $payment->reference : null,
                'payment_status' => $resource->requires_payment ? ResourceApplication::PAYMENT_STATUS_VERIFIED : null,
                'status' => ResourceApplication::STATUS_PENDING
            ]);

            return redirect()->route('farmer.resources.track')
                ->with('success', 'Application submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Application submission failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to submit application. Please try again.');
        }
    }

    /**
     * Track user's applications
     */
    public function track(Request $request)
    {
        $query = ResourceApplication::with(['resource', 'resource.partner'])
            ->where('user_id', Auth::id());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        return view('user.resources.track', compact('applications'));
    }

    /**
     * View a specific application
     */
    public function showApplication(ResourceApplication $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->load(['resource', 'resource.partner']);

        return view('user.resources.application-show', compact('application'));
    }

    /**
     * Create application from payment session data
     */
    protected function createApplicationFromPayment($sessionData, $userId, $paymentReference)
    {
        $resource = Resource::findOrFail($sessionData['resource_id']);
        
        // Move temporary files to permanent location
        $finalFormData = [];
        foreach ($sessionData['form_data'] as $key => $value) {
            if (isset($sessionData['file_paths'][$key])) {
                // Move from temp to permanent location
                $tempPath = $sessionData['file_paths'][$key]['path'];
                $newPath = str_replace('temp/', '', $tempPath);
                
                Storage::disk('public')->move($tempPath, $newPath);
                
                $finalFormData[$key] = [
                    'path' => $newPath,
                    'original_name' => $sessionData['file_paths'][$key]['original_name']
                ];
            } else {
                $finalFormData[$key] = $value;
            }
        }
        
        return ResourceApplication::create([
            'user_id' => $userId,
            'resource_id' => $resource->id,
            'form_data' => $finalFormData,
            'payment_reference' => $paymentReference,
            'payment_status' => ResourceApplication::PAYMENT_STATUS_VERIFIED,
            'status' => ResourceApplication::STATUS_PENDING
        ]);
    }

    /**
     * Validate application form data
     */
    protected function validateApplication(Request $request, Resource $resource)
    {
        $rules = [];
        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            $rules[$fieldName] = $field['required'] ? 'required' : 'nullable';
            
            if ($field['type'] === 'file') $rules[$fieldName] .= '|file|max:2048';
            if ($field['type'] === 'number') $rules[$fieldName] .= '|numeric';
            if ($field['type'] === 'select') {
                $options = is_array($field['options']) ? $field['options'] : explode(',', $field['options']);
                $rules[$fieldName] .= '|in:' . implode(',', array_map('trim', $options));
            }
        }

        return $request->validate($rules);
    }

    /**
     * Process form data including file uploads
     */
    protected function processFormData($request, Resource $resource)
    {
        $formData = [];
        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            
            if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $path = $file->store('resource-applications', 'public');
                $formData[$field['label']] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName()
                ];
            } else {
                $formData[$field['label']] = $request->input($fieldName);
            }
        }
        return $formData;
    }

    /**
     * Check if user has successfully paid for a resource
     */
    protected function hasUserPaidForResource($userId, $resourceId)
    {
        return Payment::where('customerId', $userId)
            ->where('resourceId', $resourceId)
            ->where('status', 'success')
            ->exists();
    }
}