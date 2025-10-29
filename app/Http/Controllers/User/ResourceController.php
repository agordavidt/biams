<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceApplication;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    /**
     * Display all active resources available to farmers
     */
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        $query = Resource::with(['vendor', 'partner'])
            ->availableForApplication();

        // Add search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Add filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Add filter by payment type
        if ($request->has('payment_type') && $request->payment_type) {
            if ($request->payment_type === 'free') {
                $query->where('requires_payment', false);
            } elseif ($request->payment_type === 'paid') {
                $query->where('requires_payment', true);
            }
        }

        $resources = $query->latest()->paginate(12); // Changed from get() to paginate()

        // Get user's application IDs
        $userApplications = ResourceApplication::where('user_id', $user->id)
            ->pluck('resource_id')
            ->toArray();

        return view('user.resources.index', compact('resources', 'userApplications', 'farmer'));
    }

    /**
     * Show specific resource details
     */
    public function show(Resource $resource)
    {
        if (!$resource->isActive()) {
            return redirect()->route('farmer.resources.index')
                ->with('error', 'This resource is no longer available.');
        }

        $user = Auth::user();
        $farmer = $user->farmerProfile;
        
        $resource->load(['vendor', 'partner']);
        
        // Check for existing application
        $existingApplication = ResourceApplication::where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->first();

        // Check payment status for paid resources
        $hasPaid = false;
        if ($resource->requires_payment) {
            $hasPaid = $this->hasUserPaidForResource($user->id, $resource->id);
        }

        return view('user.resources.show', compact('resource', 'existingApplication', 'hasPaid', 'farmer'));
    }

    /**
     * Show application form (or payment page if required)
     */
    public function apply(Resource $resource)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;
        
        // Check if already applied
        if ($resource->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->route('farmer.resources.track')
                ->with('error', 'You have already applied for this resource.');
        }

        // Check if resource is still available
        if (!$resource->isAvailableForApplication()) {
            return redirect()->route('farmer.resources.index')
                ->with('error', 'This resource is no longer available.');
        }

        // For paid resources, check payment status
        $hasPaid = false;
        if ($resource->requires_payment) {
            $hasPaid = $this->hasUserPaidForResource($user->id, $resource->id);
        }

        return view('user.resources.apply', compact('resource', 'hasPaid', 'farmer'));
    }

    /**
     * Initiate payment for paid resource
     */
    public function initiatePayment(Request $request, Resource $resource)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;
        
        // Verify resource requires payment
        if (!$resource->requires_payment) {
            return redirect()->route('farmer.resources.apply', $resource)
                ->with('error', 'This resource does not require payment.');
        }

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

        // Validate quantity for resources that require it
        if ($resource->requires_quantity) {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:' . $resource->max_per_farmer,
            ]);
        }

        try {
            $credoPublicKey = config('services.credo.public_key');
            $credoBaseUrl = config('services.credo.base_url');
            
            Log::info('Credo Payment Configuration:', [
                'base_url' => $credoBaseUrl,
                'public_key' => $credoPublicKey ? substr($credoPublicKey, 0, 10) . '...' : 'NOT_SET'
            ]);

            // Calculate amount
            $quantity = $resource->requires_quantity ? $request->quantity : 1;
            $totalAmount = $resource->price * $quantity;

            $reference = 'RES-' . $user->id . '-' . $resource->id . '-' . time();
            
            $requestData = [
                'amount' => (int) round($totalAmount * 100), // Convert to kobo
                'email' => $user->email,
                'customerFirstName' => $user->first_name ?? $farmer->first_name ?? 'Farmer',
                'customerLastName' => $user->last_name ?? $farmer->last_name ?? 'User',
                'customerPhoneNumber' => $user->phone ?? $farmer->phone_number ?? '2348000000000',
                'currency' => 'NGN',
                'channels' => ['card', 'bank'],
                'reference' => $reference,
                'bearer' => 0,
                'callbackUrl' => route('farmer.payment.callback'),
                'metadata' => [
                    'payment_id' => 'resource_' . $resource->id,
                    'user_id' => $user->id,
                    'farmer_id' => $farmer->id ?? null,
                    'resource_id' => $resource->id,
                    'resource_name' => $resource->name,
                    'quantity' => $quantity,
                ]
            ];
            
            Log::info('Credo Payment Request:', $requestData);
            
            $response = Http::asJson()->withHeaders([
                'Authorization' => $credoPublicKey, 
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($credoBaseUrl . '/transaction/initialize', $requestData);
            
            Log::info('Credo Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Credo Success Response:', $data);
                
                if (isset($data['data']['authorizationUrl'])) {
                    // Store payment reference in session
                    session([
                        'current_payment_reference' => $reference,
                        'payment_quantity' => $quantity
                    ]);
                    
                    return redirect($data['data']['authorizationUrl']);
                }
                
                return redirect()->back()
                    ->with('error', 'Unable to initialize payment. Please try again.');
                
            } else {
                Log::error('Credo initialization failed:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return redirect()->back()
                    ->with('error', 'Payment gateway error. Please try again later.');
            }
            
        } catch (\Exception $e) {
            Log::error('Payment error: ' . $e->getMessage(), [
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
        Log::info('Payment callback received', ['query' => $request->query()]);
        
        $reference = $request->query('reference')
            ?? $request->query('ref')
            ?? $request->query('payment_reference');
        
        $transRef = $request->query('transRef')
            ?? $request->query('transactionReference')
            ?? $request->query('credoReference')
            ?? session('current_payment_reference');

        if (!$transRef) {
            return redirect()->route('farmer.resources.index')
                ->with('error', 'Invalid payment reference.');
        }

        try {
            $verificationResponse = $this->verifyCredoPayment($transRef);

            if ($verificationResponse['success']) {
                $data = $verificationResponse['data'];
                $paymentData = data_get($data, 'data') ?? $data;
                
                // Extract metadata
                $metadataArray = data_get($data, 'data.data.metadata') 
                    ?? data_get($data, 'data.metadata') 
                    ?? data_get($data, 'metadata');
                
                $resourceId = null;
                $userId = null;
                $farmerId = null;
                $quantity = session('payment_quantity', 1);
                
                // Parse metadata
                if (is_array($metadataArray)) {
                    foreach ($metadataArray as $item) {
                        if (is_array($item)) {
                            if (data_get($item, 'insightTag') === 'resource_id') {
                                $resourceId = data_get($item, 'insightTagValue');
                            }
                            if (data_get($item, 'insightTag') === 'user_id') {
                                $userId = data_get($item, 'insightTagValue');
                            }
                            if (data_get($item, 'insightTag') === 'farmer_id') {
                                $farmerId = data_get($item, 'insightTagValue');
                            }
                            if (data_get($item, 'insightTag') === 'quantity') {
                                $quantity = data_get($item, 'insightTagValue');
                            }
                        }
                    }
                    // Handle associative metadata
                    if (!$resourceId) $resourceId = data_get($metadataArray, 'resource_id');
                    if (!$userId) $userId = data_get($metadataArray, 'user_id');
                    if (!$farmerId) $farmerId = data_get($metadataArray, 'farmer_id');
                    if (!$quantity) $quantity = data_get($metadataArray, 'quantity', 1);
                }
                
                if (!$resourceId || !$userId) {
                    return redirect()->route('farmer.resources.index')
                        ->with('error', 'Invalid payment data.');
                }
                
                $resource = Resource::find($resourceId);
                
                if (!$resource) {
                    return redirect()->route('farmer.resources.index')
                        ->with('error', 'Resource not found.');
                }
                
                // Check if payment already exists
                $existingPayment = Payment::where('reference', $transRef)->first();
                
                if (!$existingPayment) {
                    $amount = data_get($paymentData, 'amount', 0) / 100;
                    
                    // Log payment
                    Payment::create([
                        'businessName' => 'BIAMS',
                        'reference' => $transRef,
                        'transAmount' => $amount,
                        'transFee' => data_get($paymentData, 'fee') ?? 0,
                        'transTotal' => $amount,
                        'transDate' => now(),
                        'settlementAmount' => $amount,
                        'status' => 'success',
                        'statusMessage' => data_get($paymentData, 'message') ?? 'Payment successful',
                        'customerId' => $userId,
                        'resourceId' => $resource->id,
                        'resourceOwnerId' => $resource->vendor_id ?? $resource->partner_id ?? 1,
                        'channelId' => data_get($paymentData, 'channel') ?? 'WEB',
                        'currencyCode' => 'NGN',
                        'credo_reference' => $transRef,
                        'payment_data' => $paymentData
                    ]);
                }
                
                // Clear session
                session()->forget(['current_payment_reference', 'payment_quantity']);
                
                return redirect()->route('farmer.resources.apply', $resource)
                    ->with('success', 'Payment successful! Please complete your application form below.');
                    
            } else {
                Log::error('Payment verification failed', [
                    'transRef' => $transRef,
                    'reference' => $reference,
                ]);
                
                return redirect()->route('farmer.resources.index')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }
            
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'reference' => $reference,
                'transRef' => $transRef,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('farmer.resources.index')
                ->with('error', 'Error processing payment. Please contact support.');
        }
    }

    /**
     * Verify payment with Credo API
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
            
            Log::info('Credo Verify Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Credo Verify Response:', $data);
                
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
            
            Log::error('Credo Verify failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return [
                'success' => false,
                'message' => 'Payment verification failed'
            ];
            
        } catch (\Exception $e) {
            Log::error('Credo verification error: ' . $e->getMessage());
            
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
        $farmer = $user->farmerProfile;
        
        // Check if already applied
        if ($resource->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->route('farmer.resources.track')
                ->with('error', 'You have already applied for this resource.');
        }
        
        // Validate quantity if resource requires it
        if ($resource->requires_quantity) {
            $request->validate([
                'quantity_requested' => 'required|integer|min:1|max:' . $resource->max_per_farmer,
            ]);
        }

        // If requires payment, verify payment exists
        $paymentReference = null;
        $quantityPaid = null;
        
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

            $paymentReference = $payment->reference;
            $quantityPaid = session('payment_quantity', 1);
        }
        
        // Validate the dynamic form data
        $this->validateApplication($request, $resource);

        DB::beginTransaction();

        try {
            // Process form data including file uploads
            $formData = $this->processFormData($request, $resource);
            
            // Create the application
            $application = ResourceApplication::create([
                'user_id' => $user->id,
                'farmer_id' => $farmer->id ?? null,
                'resource_id' => $resource->id,
                'form_data' => $formData,
                'quantity_requested' => $resource->requires_quantity ? $request->quantity_requested : null,
                'unit_price' => $resource->price,
                'payment_reference' => $paymentReference,
                'payment_status' => $resource->requires_payment ? ResourceApplication::PAYMENT_STATUS_VERIFIED : null,
                'status' => ResourceApplication::STATUS_PENDING
            ]);

            // If payment was made, update quantity_paid
            if ($paymentReference && $quantityPaid) {
                $application->update(['quantity_paid' => $quantityPaid]);
            }

            DB::commit();

            // Clear session data
            session()->forget('payment_quantity');

            return redirect()->route('farmer.resources.track')
                ->with('success', 'Application submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Application submission failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to submit application. Please try again.');
        }
    }

    /**
     * Track user's applications
     */
    public function track(Request $request)
    {
        $user = Auth::user();
        $query = ResourceApplication::with(['resource.vendor', 'resource.partner'])
            ->where('user_id', $user->id);

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

        $application->load(['resource.vendor', 'resource.partner', 'reviewedBy', 'fulfilledBy']);

        return view('user.resources.application-show', compact('application'));
    }

    /**
     * Cancel pending application
     */
    public function cancelApplication(ResourceApplication $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        if (!$application->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'This application cannot be cancelled.');
        }

        try {
            $application->update(['status' => ResourceApplication::STATUS_CANCELLED]);

            // If resource has quantity management, restore stock
            if ($application->resource->requires_quantity && $application->quantity_approved) {
                $application->resource->incrementStock($application->quantity_approved);
            }

            return redirect()->route('farmer.resources.track')
                ->with('success', 'Application cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error cancelling application: ' . $e->getMessage());
        }
    }

    /**
     * Validate application form data based on dynamic form fields
     */
    protected function validateApplication(Request $request, Resource $resource)
    {
        $rules = [];
        
        if (!$resource->form_fields) {
            return $request->validate($rules);
        }

        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            $rules[$fieldName] = $field['required'] ? 'required' : 'nullable';
            
            if ($field['type'] === 'file') {
                $rules[$fieldName] .= '|file|max:2048';
            }
            if ($field['type'] === 'number') {
                $rules[$fieldName] .= '|numeric';
            }
            if ($field['type'] === 'email') {
                $rules[$fieldName] .= '|email';
            }
            if ($field['type'] === 'select' && isset($field['options'])) {
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
        
        if (!$resource->form_fields) {
            return $formData;
        }

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