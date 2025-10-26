<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ResourceApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function initiatePayment(ResourceApplication $application)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        // Verify application belongs to this farmer
        if ($application->farmer_id !== $farmer->id) {
            return redirect()->route('farmer.resources.my-applications')
                ->with('error', 'Unauthorized access.');
        }

        // Only approved applications can be paid
        if (!$application->canBePaid()) {
            return redirect()->back()
                ->with('error', 'This application cannot be paid at this time.');
        }

        $application->load(['resource.vendor']);

        return view('farmer.payments.initiate', compact('application', 'farmer'));
    }

    public function processPayment(Request $request, ResourceApplication $application)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        // Verify application belongs to this farmer
        if ($application->farmer_id !== $farmer->id) {
            return redirect()->route('farmer.resources.my-applications')
                ->with('error', 'Unauthorized access.');
        }

        // Only approved applications can be paid
        if (!$application->canBePaid()) {
            return redirect()->back()
                ->with('error', 'This application cannot be paid at this time.');
        }

        $request->validate([
            'quantity_to_pay' => 'required|integer|min:1|max:' . $application->quantity_approved,
            'payment_method' => 'required|in:bank_transfer,card,ussd,mobile_money',
        ]);

        try {
            // Calculate payment amount
            $quantityToPay = $request->quantity_to_pay;
            $amountToPay = $quantityToPay * $application->unit_price;

            // Generate payment reference
            $paymentReference = 'PAY-' . strtoupper(Str::random(12));

            /**
             * TODO: Integrate with actual payment gateway
             * - Paystack
             * - Flutterwave
             * - Interswitch
             * 
             * For now, we're mocking the payment as successful
             */

            // Mock payment success
            $application->update([
                'quantity_paid' => $quantityToPay,
                'amount_paid' => $amountToPay,
                'payment_reference' => $paymentReference,
                'paid_at' => now(),
                'status' => 'paid',
            ]);

            return redirect()->route('farmer.payments.success', $application)
                ->with('success', 'Payment successful! Your resource is ready for collection.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function paymentSuccess(ResourceApplication $application)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        // Verify application belongs to this farmer
        if ($application->farmer_id !== $farmer->id) {
            return redirect()->route('farmer.resources.my-applications')
                ->with('error', 'Unauthorized access.');
        }

        $application->load(['resource.vendor']);

        return view('farmer.payments.success', compact('application', 'farmer'));
    }

    public function verifyPayment(Request $request)
    {
        // TODO: Implement payment gateway callback verification
        // This endpoint will be called by the payment gateway
        
        $reference = $request->reference;
        
        // Verify with payment gateway
        // Update application status based on verification
        
        return response()->json(['status' => 'success']);
    }
}