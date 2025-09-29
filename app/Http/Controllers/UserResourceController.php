<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceApplication;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UserResourceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $resources = Resource::query()
            ->active()
            ->forUserPractice($user)
            ->with(['applications' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->get();

        return view('user.resources.index', compact('resources'));
    }

    public function show(Resource $resource)
    {
        $user = Auth::user();
        $existingApplication = $resource->applications()
            ->where('user_id', $user->id)
            ->first();

        $hasPaid = $this->hasUserPaidForResource($user->id, $resource->id);
        
        if ($resource->requires_payment && !$hasPaid) {
            return $this->initiatePayment(new Request(), $resource);
        }

        return view('user.resources.show', compact('resource', 'existingApplication', 'hasPaid'));
    }

    public function apply(Resource $resource)
    {
        $user = Auth::user();
        
        if ($resource->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                ->with('error', 'You have already applied for this resource.');
        }

        $hasPaid = $this->hasUserPaidForResource($user->id, $resource->id);
        
        if ($resource->requires_payment && !$hasPaid) {
            return $this->initiatePayment(new Request(), $resource);
        }

        return view('user.resources.apply', compact('resource', 'hasPaid'));
    }

    public function initiatePayment(Request $request, Resource $resource)
    {
        $user = Auth::user();
        $reference = 'RES-' . $user->id . '-' . time();
        
        // Process form data, handling file uploads separately
        $formData = $request->except(['_token', 'payment_reference']);
        $filePaths = [];

        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            if ($field['type'] === 'file' && $request->hasFile($fieldName)) {
                // Store the file temporarily and save the path
                $path = $request->file($fieldName)->store('temp/resource-applications', 'public');
                $filePaths[$fieldName] = $path;
                $formData[$fieldName] = $path; // Replace UploadedFile with path
            }
        }

        try {
            // Debug: Log the API key (first few characters only for security)
            $apiKey = env('CREDO_PUBLIC_KEY');
            Log::info('Credo API Key (first 10 chars): ' . substr($apiKey, 0, 10));
            Log::info('Credo URL: ' . env('CREDO_URL'));
            
            $response = Http::timeout(30)
                ->retry(3, 1000) // Retry 3 times with 1 second delay
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
                        'form_data' => json_encode($formData),
                        'file_paths' => json_encode($filePaths), // Store file paths separately
                    ],
                    'amount' => ($resource->price * 100),
                    'reference' => $reference,
                    'callbackUrl' => route('payment.callback'),
                    'bearer' => 0,
                ]);
            
            
            $responseData = $response->collect('data');
            
            if (isset($responseData['authorizationUrl'])) {
                // Store form data and file paths in session
                session()->put('resource_form_data.' . $reference, [
                    'resource_id' => $resource->id,
                    'form_data' => $formData,
                    'file_paths' => $filePaths,
                ]);
                
                return redirect($responseData['authorizationUrl']);
            }
            
            // Clean up temporary files if payment initialization fails
            foreach ($filePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            
            return redirect()->back()->with('error', 'Credo payment gateway took too long to respond.');
            
        } catch (\Exception $e) {
            // Clean up temporary files on error
            foreach ($filePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            
            // Log::error('Error initializing payment gateway: ' . $e->getMessage(), [
            //     'user_id' => $user->id,
            //     'resource_id' => $resource->id,
            //     'reference' => $reference,
            //     'error_type' => get_class($e)
            // ]);
            
            return redirect()->back()->with('error', 'Unable to process payment at this time. Please try again later or contact support if the issue persists.');
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        // Verify the transaction with Credo
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
                return redirect()->route('user.resources.index')
                    ->with('error', 'Payment verification failed. Please try again.');
            }
            
            $paymentData = $response->json('data');
            
            // Extract payment status and message
            $status = $paymentData['status'];
            $message = $paymentData['statusMessage'] == 'Successfully processed' ? 'Successful' : 'Failed';
            
            if ($message == 'Successful') {
                // Get the form data and file paths from session
                $sessionData = session()->get('resource_form_data.' . $request->reference);
                
                if (!$sessionData) {
                    return redirect()->route('user.resources.index')
                        ->with('error', 'Application data not found. Please try again.');
                }
                
                $resource = Resource::findOrFail($sessionData['resource_id']);
                $user = Auth::user();
                
                // Log payment information to payments table
                Payment::create([
                    'businessName' =>  'BIAMS',
                    'reference' => $request->reference,
                    'transAmount' => $resource->price,
                    'transFee' => 0,
                    'transTotal' => $resource->price,
                    'transDate' => now(),
                    'settlementAmount' => $resource->price,
                    'status' => 'success', 
                    'statusMessage' => $paymentData['statusMessage'] ?? 'Payment successful',
                    'customerId' => $user->id,
                    'resourceId' => $resource->id,
                    'resourceOwnerId' => $resource->partner->user_id ?? 1, 
                    'channelId' => 'WEB',
                    'currencyCode' => 'NGN'
                ]);
                
                // Clean up session data
                session()->forget('resource_form_data.' . $request->reference);
                
                return redirect()->route('user.resources.apply', $resource)
                    ->with('success', 'Payment successful! You can now complete the application form.');
            } else {
                // Clean up temporary files on failed payment
                $sessionData = session()->get('resource_form_data.' . $request->reference);
                if ($sessionData && isset($sessionData['file_paths'])) {
                    foreach ($sessionData['file_paths'] as $path) {
                        Storage::disk('public')->delete($path);
                    }
                }
                
                return redirect()->route('user.resources.index')
                    ->with('error', 'Payment failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            // Clean up temporary files on error
            $sessionData = session()->get('resource_form_data.' . $request->reference);
            if ($sessionData && isset($sessionData['file_paths'])) {
                foreach ($sessionData['file_paths'] as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            
            // Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('user.resources.index')
                ->with('error', 'Error processing payment callback. Please contact support.');
        }
    }

    public function submit(Request $request, Resource $resource)
    {
        // Check if this is a paid resource and user has completed payment
        if ($resource->requires_payment) {
            // Verify payment exists and is successful
            $payment = Payment::where('customerId', Auth::id())
                ->where('resourceId', $resource->id)
                ->where('status', 'success')
                ->whereDoesntHave('resource.applications', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->first();
                
            if (!$payment) {
                return redirect()->back()->with('error', 'Payment verification failed. Please complete payment first.');
            }
        }
        
        // Validate the application data
        $validated = $this->validateApplication($request, $resource);

        try {
            // Create the application
            $application = ResourceApplication::create([
                'user_id' => Auth::id(),
                'resource_id' => $resource->id,
                'form_data' => $this->processFormData($request, $resource),
                'payment_reference' => $resource->requires_payment ? $payment->reference : null,
                'payment_status' => $resource->requires_payment ? ResourceApplication::PAYMENT_STATUS_VERIFIED : null,
                'status' => ResourceApplication::STATUS_PENDING
            ]);

            return redirect()->route('user.resources.index')
                ->with('success', 'Application submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Application submission failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit application. Please try again.');
        }
    }

    public function track()
    {
        $applications = ResourceApplication::with('resource')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.resources.track', compact('applications'));
    }

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
                $rules[$fieldName] .= '|in:' . implode(',', $options);
            }
        }

        return $request->validate($rules);
    }

    protected function processFormData($request, Resource $resource)
    {
        $formData = [];
        foreach ($resource->form_fields as $field) {
            $fieldName = Str::slug($field['label']);
            
            if ($field['type'] === 'file' && isset($request[$fieldName]) && $request[$fieldName] instanceof \Illuminate\Http\UploadedFile) {
                $path = $request[$fieldName]->store('resource-applications', 'public');
                $formData[$field['label']] = $path;
            } else {
                $formData[$field['label']] = $request[$fieldName] ?? null;
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