<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
        $existingApplication = $resource->applications()
            ->where('user_id', Auth::id())
            ->first();

        return view('user.resources.show', compact('resource', 'existingApplication'));
    }

    public function apply(Resource $resource)
    {
        if ($resource->applications()->where('user_id', Auth::id())->exists()) {
            return redirect()->back()
                ->with('error', 'You have already applied for this resource.');
        }

        return view('user.resources.apply', compact('resource'));
    }

    public function initiatePayment(Request $request, Resource $resource)
    {
        $user = Auth::user();
        $reference = 'RES-' . $user->id . '-' . time();
        
        try {
            $response = Http::accept('application/json')->withHeaders([
                'authorization' => env('CREDO_PUBLIC_KEY'),
                'content_type' => 'application/json',
            ])->post(env('CREDO_URL') . '/transaction/initialize', [
                'email' => $user->email,
                'metadata' => [
                    'resource_id' => $resource->id,
                    'user_id' => $user->id,
                    'form_data' => json_encode($request->except(['_token', 'payment_reference']))
                ],
                'amount' => ($resource->price * 100),
                'reference' => $reference,
                'callbackUrl' => route('payment.callback'),
                'bearer' => 0,
            ]);
            
            $responseData = $response->collect('data');
            
            if (isset($responseData['authorizationUrl'])) {
                // Store temporary form data in session
                session()->put('resource_form_data.' . $reference, [
                    'resource_id' => $resource->id,
                    'form_data' => $request->except(['_token', 'payment_reference']),
                ]);
                
                return redirect($responseData['authorizationUrl']);
            }
            
            return redirect()->back()->with('error', 'Credo payment gateway took too long to respond.');
            
        } catch (\Exception $e) {
            Log::error('Error initializing payment gateway: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error initializing payment gateway. Please try again.');
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        // Verify the transaction with Credo
        try {
            $response = Http::accept('application/json')->withHeaders([
                'authorization' => env('CREDO_SECRET_KEY'),
                'content-type' => 'application/json',
            ])->get(env('CREDO_URL') . "/transaction/{$request->reference}/verify");
            
            if (!$response->successful()) {
                return redirect()->route('user.resources.index')
                    ->with('error', 'Payment verification failed. Please try again.');
            }
            
            $paymentData = $response->json('data');
            
            // Extract payment status and message
            $status = $paymentData['status'];
            $message = $paymentData['statusMessage'] == 'Successfully processed' ? 'Successful' : 'Failed';
            
            if ($message == 'Successful') {
                // Get the form data from session
                $formData = session()->get('resource_form_data.' . $request->reference);
                
                if (!$formData) {
                    return redirect()->route('user.resources.index')
                        ->with('error', 'Application data not found. Please try again.');
                }
                
                $resource = Resource::findOrFail($formData['resource_id']);
                
                // Create the application
                $application = ResourceApplication::create([
                    'user_id' => Auth::id(),
                    'resource_id' => $resource->id,
                    'form_data' => $this->processFormData($formData['form_data'], $resource),
                    'payment_reference' => $request->reference,
                    'payment_status' => ResourceApplication::PAYMENT_STATUS_VERIFIED,
                    'status' => ResourceApplication::STATUS_PENDING
                ]);
                
                // Clean up session data
                session()->forget('resource_form_data.' . $request->reference);
                
                return redirect()->route('user.resources.track')
                    ->with('success', 'Payment successful and application submitted!');
            } else {
                return redirect()->route('user.resources.index')
                    ->with('error', 'Payment failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('user.resources.index')
                ->with('error', 'Error processing payment callback. Please contact support.');
        }
    }

    public function submit(Request $request, Resource $resource)
    {
        // Only for non-payment resources
        if ($resource->requires_payment) {
            return $this->initiatePayment($request, $resource);
        }
        
        // Validate the application data
        $validated = $this->validateApplication($request, $resource);

        try {
            // Create the application
            $application = ResourceApplication::create([
                'user_id' => Auth::id(),
                'resource_id' => $resource->id,
                'form_data' => $this->processFormData($request, $resource),
                'payment_reference' => null,
                'payment_status' => null,
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
}