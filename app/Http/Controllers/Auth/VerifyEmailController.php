<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import the Log facade

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Debugging: Log the start of the verification process
        Log::info('Email verification process started.');

        // Debugging: Check if the request has a valid signature
        if (!$request->hasValidSignature()) {
            Log::error('Invalid or expired verification link.');
            abort(403, 'Invalid or expired verification link.');
        }

        // Debugging: Check if the user is authenticated
        if (!$request->user()) {
            Log::error('User not authenticated.');
            abort(403, 'User not authenticated.');
        }

        $user = $request->user();

        // Debugging: Log the user's current verification status
        Log::info('User email verification status before verification:', [
            'email_verified_at' => $user->email_verified_at,
        ]);

        // Debugging: Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('Email is already verified.');
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        // Debugging: Attempt to mark the email as verified
        if ($user->markEmailAsVerified()) {
            Log::info('Email marked as verified successfully.');
            event(new Verified($user));
        } else {
            Log::error('Failed to mark email as verified.');
        }

        // Debugging: Log the user's verification status after attempting to verify
        Log::info('User email verification status after verification:', [
            'email_verified_at' => $user->email_verified_at,
        ]);

        // Debugging: Log the user in
        Auth::login($user);
        Log::info('User logged in after verification.');

        // Redirect to the profile completion page
        return redirect()->route('profile.complete')->with('status', 'Email verified successfully! Please complete your profile.');
    }
}