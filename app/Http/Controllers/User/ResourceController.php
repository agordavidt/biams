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
     * Show application form for a resource (or payment page if payment required)
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

        try {
            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->acceptJson()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.credo.key'),
                    'Content-Type' => 'application/json',
                ])
                ->post(config('services.credo.base_url') . '/transaction/initialize', [
                    'email' => $user->email,
                    'amount' => ($resource->price * 100), // Amount in kobo
                    'currency' => 'NGN',
                    'reference' => $reference,
                    'callback_url' => route('farmer.payment.callback'),
                    'metadata' => [
                        'resource_id' => $resource->id,
                        'user_id' => $user->id,
                        'resource_name' => $resource->name,
                    ],
                ]);
            
            if (!$response->successful()) {
                Log::error('Credo payment initialization failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'reference' => $reference,
                ]);
                
                return redirect()->back()
                    ->with('error', 'Payment gateway error. Please try again later.');
            }
            
            $responseData = $response->json();
            
            if (isset($responseData['data']['authorizationUrl'])) {
                return redirect($responseData['data']['authorizationUrl']);
            }
            
            return redirect()->back()
                ->with('error', 'Unable to initialize payment. Please try again.');
            
        } catch (\Exception $e) {
            Log::error('Error initializing payment: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'reference' => $reference,
            ]);
            
            return redirect()->back()
                ->with('error', 'Unable to process payment at this time. Please try again later.');
        }
    }

    /**
     * Handle payment callback from Credo
     */
    public function handlePaymentCallback(Request $request)
    {
        try {
            $reference = $request->reference;
            
            if (!$reference) {
                return redirect()->route('farmer.resources.index')
                    ->with('error', 'Invalid payment reference.');
            }

            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->acceptJson()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.credo.secret'),
                    'Content-Type' => 'application/json',
                ])
                ->get(config('services.credo.base_url') . "/transaction/verify/{$reference}");
            
            if (!$response->successful()) {
                Log::error('Payment verification failed', [
                    'reference' => $reference,
                    'status' => $response->status(),
                ]);
                
                return redirect()->route('farmer.resources.index')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }
            
            $paymentData = $response->json('data');
            $status = $paymentData['status'] ?? 'failed';
            $message = $paymentData['message'] ?? 'Payment failed';
            
            // Credo uses 'success' or 'successful' status
            if (in_array(strtolower($status), ['success', 'successful'])) {
                $metadata = $paymentData['metadata'] ?? [];
                $resourceId = $metadata['resource_id'] ?? null;
                
                if (!$resourceId) {
                    return redirect()->route('farmer.resources.index')
                        ->with('error', 'Invalid payment data.');
                }
                
                $resource = Resource::find($resourceId);
                
                if (!$resource) {
                    return redirect()->route('farmer.resources.index')
                        ->with('error', 'Resource not found.');
                }
                
                $user = Auth::user();
                
                // Check if payment already exists
                $existingPayment = Payment::where('reference', $reference)->first();
                
                if (!$existingPayment) {
                    // Log payment to payments table
                    Payment::create([
                        'businessName' => 'BIAMS',
                        'reference' => $reference,
                        'transAmount' => $resource->price,
                        'transFee' => $paymentData['fee'] ?? 0,
                        'transTotal' => $paymentData['amount'] / 100, // Convert from kobo
                        'transDate' => now(),
                        'settlementAmount' => $resource->price,
                        'status' => 'success',
                        'statusMessage' => $message,
                        'customerId' => $user->id,
                        'resourceId' => $resource->id,
                        'resourceOwnerId' => $resource->partner_id ?? 1,
                        'channelId' => $paymentData['channel'] ?? 'WEB',
                        'currencyCode' => 'NGN'
                    ]);
                }
                
                return redirect()->route('farmer.resources.apply', $resource)
                    ->with('success', 'Payment successful! Please complete your application form below.');
                    
            } else {
                return redirect()->route('farmer.resources.index')
                    ->with('error', 'Payment was not successful. Please try again.');
            }
            
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'reference' => $request->reference,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('farmer.resources.index')
                ->with('error', 'Error processing payment callback. Please contact support with reference: ' . $request->reference);
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
                return redirect()->back()
                    ->with('error', 'Payment required. Please complete payment first.');
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