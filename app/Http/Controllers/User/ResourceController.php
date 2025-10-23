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
     * Initiate payment for a resource (UPDATED to match mentee system)
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

        try {
            $credoPublicKey = config('services.credo.public_key');
            $credoBaseUrl = config('services.credo.base_url');
            
            // Log credentials for debugging (mask sensitive data)
            Log::info('Farmer Credo API Configuration:', [
                'base_url' => $credoBaseUrl,
                'public_key' => $credoPublicKey ? substr($credoPublicKey, 0, 10) . '...' : 'NOT_SET'
            ]);

            $reference = 'RES-' . $user->id . '-' . $resource->id . '-' . time();
            
            $requestData = [
                'amount' => (int) round($resource->price * 100), // Convert to kobo
                'email' => $user->email,
                'customerFirstName' => $user->first_name ?? 'Customer',
                'customerLastName' => $user->last_name ?? 'User',
                'customerPhoneNumber' => $user->phone ?? '2348000000000',
                'currency' => 'NGN',
                'channels' => ['card', 'bank'],
                'reference' => $reference,
                'bearer' => 0,
                'callbackUrl' => route('farmer.payment.callback'),
                'metadata' => [
                    'payment_id' => 'resource_' . $resource->id, // Custom identifier
                    'user_id' => $user->id,
                    'resource_id' => $resource->id,
                    'resource_name' => $resource->name,
                ]
            ];
            
            Log::info('Farmer Credo API Request:', $requestData);
            
            $response = Http::asJson()->withHeaders([
                'Authorization' => $credoPublicKey, 
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($credoBaseUrl . '/transaction/initialize', $requestData);
            
            Log::info('Farmer Credo API Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Farmer Credo API Success Response:', $data);
                
                if (isset($data['data']['authorizationUrl'])) {
                    // Store payment reference in session temporarily for verification
                    session(['current_payment_reference' => $reference]);
                    
                    return redirect($data['data']['authorizationUrl']);
                }
                
                return redirect()->back()
                    ->with('error', 'Unable to initialize payment. Please try again.');
                
            } else {
                Log::error('Farmer Credo initialization failed:', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body()
                ]);
                
                return redirect()->back()
                    ->with('error', 'Payment gateway error. Please try again later.');
            }
            
        } catch (\Exception $e) {
            Log::error('Farmer Credo API error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'resource_id' => $resource->id,
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
        // Log incoming callback query for diagnostics
        Log::info('Farmer Credo callback received', [
            'query' => $request->query(),
        ]);
        
        $reference = $request->query('reference')
            ?? $request->query('ref')
            ?? $request->query('payment_reference');
        
        $transRef = $request->query('transRef')
            ?? $request->query('transactionReference')
            ?? $request->query('credoReference');
        
        if (!$transRef && $reference) {
            // Try to get transaction reference from our stored data
            $transRef = session('current_payment_reference');
        }

        if (!$transRef) {
            return redirect()->route('farmer.resources.index')
                ->with('error', 'Invalid payment reference.');
        }

        try {
            $verificationResponse = $this->verifyCredoPayment($transRef);

            if ($verificationResponse['success']) {
                $data = $verificationResponse['data'];
                $paymentData = data_get($data, 'data') ?? $data;
                
                // Extract metadata from verification response
                $metadataArray = data_get($data, 'data.data.metadata') 
                    ?? data_get($data, 'data.metadata') 
                    ?? data_get($data, 'metadata');
                
                $resourceId = null;
                $userId = null;
                
                // Handle different metadata formats
                if (is_array($metadataArray)) {
                    foreach ($metadataArray as $item) {
                        if (is_array($item) && data_get($item, 'insightTag') === 'resource_id') {
                            $resourceId = data_get($item, 'insightTagValue');
                        }
                        if (is_array($item) && data_get($item, 'insightTag') === 'user_id') {
                            $userId = data_get($item, 'insightTagValue');
                        }
                    }
                    // Handle associative metadata
                    if (!$resourceId) $resourceId = data_get($metadataArray, 'resource_id');
                    if (!$userId) $userId = data_get($metadataArray, 'user_id');
                }
                
                if (!$resourceId || !$userId) {
                    return redirect()->route('farmer.resources.index')
                        ->with('error', 'Invalid payment data.');
                }
                
                $resource = Resource::find($resourceId);
                $user = Auth::user();
                
                if (!$resource) {
                    return redirect()->route('farmer.resources.index')
                        ->with('error', 'Resource not found.');
                }
                
                // Check if payment already exists
                $existingPayment = Payment::where('reference', $transRef)->first();
                
                if (!$existingPayment) {
                    // Log payment to payments table
                    Payment::create([
                        'businessName' => 'BIAMS',
                        'reference' => $transRef,
                        'transAmount' => $resource->price,
                        'transFee' => data_get($paymentData, 'fee') ?? 0,
                        'transTotal' => data_get($paymentData, 'amount', 0) / 100, // Convert from kobo
                        'transDate' => now(),
                        'settlementAmount' => $resource->price,
                        'status' => 'success',
                        'statusMessage' => data_get($paymentData, 'message') ?? 'Payment successful',
                        'customerId' => $userId,
                        'resourceId' => $resource->id,
                        'resourceOwnerId' => $resource->partner_id ?? 1,
                        'channelId' => data_get($paymentData, 'channel') ?? 'WEB',
                        'currencyCode' => 'NGN',
                        'credo_reference' => $transRef,
                        'payment_data' => $paymentData
                    ]);
                }
                
                // Clear session reference
                session()->forget('current_payment_reference');
                
                return redirect()->route('farmer.resources.apply', $resource)
                    ->with('success', 'Payment successful! Please complete your application form below.');
                    
            } else {
                Log::error('Farmer payment verification failed', [
                    'transRef' => $transRef,
                    'reference' => $reference,
                ]);
                
                return redirect()->route('farmer.resources.index')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }
            
        } catch (\Exception $e) {
            Log::error('Farmer payment callback error: ' . $e->getMessage(), [
                'reference' => $reference,
                'transRef' => $transRef,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('farmer.resources.index')
                ->with('error', 'Error processing payment. Please contact support.');
        }
    }

    /**
 * Verify payment with Credo API (Same as mentee system)
    */
    private function verifyCredoPayment($transRef)
    {
        try {
            $credoSecretKey = config('services.credo.secret_key');
            $credoBaseUrl = config('services.credo.base_url');
            
            $response = Http::withHeaders([
                'Authorization' => $credoSecretKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->get($credoBaseUrl . '/transaction/' . $transRef . '/verify');
            
            Log::info('Farmer Credo Verify Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Farmer Credo Verify Response Body:', $data);
                
                $statusRaw = data_get($data, 'data.status');
                $statusLower = is_string($statusRaw) ? strtolower($statusRaw) : null;
                $statusIsZero = ((string) $statusRaw) === '0';
                $statusMessageRaw = (string) (data_get($data, 'data.statusMessage') ?? data_get($data, 'data.message') ?? '');
                $statusMessage = strtolower($statusMessageRaw);
                $statusCode = data_get($data, 'data.statusCode')
                    ?? data_get($data, 'data.code')
                    ?? data_get($data, 'data.responseCode');
                
                $approvedByMessage = str_contains($statusMessage, 'success')
                    || str_contains($statusMessage, 'approved');
                
                $isApproved = $statusIsZero
                    || in_array($statusLower, ['success', 'successful', 'approved', 'completed', 'paid'], true)
                    || in_array((string) $statusCode, ['0', '00'], true)
                    || $approvedByMessage;
                
                if ($isApproved) {
                    return [
                        'success' => true,
                        'transaction_id' => data_get($data, 'data.id') ?? data_get($data, 'data.transRef'),
                        'data' => $data
                    ];
                }
            }
            
            Log::error('Farmer Credo Verify failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return [
                'success' => false,
                'message' => 'Payment verification failed'
            ];
            
        } catch (\Exception $e) {
            Log::error('Farmer Credo verification error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Verification service unavailable'
            ];
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