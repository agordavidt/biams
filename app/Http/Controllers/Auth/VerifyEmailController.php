<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        Log::info('Email verification process started.');

        // Get the user from the request (this handles the signed URL verification)
        $user = $request->user();
        
        if (!$user) {
            Log::error('User not found during email verification.');
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        Log::info('User email verification status before verification:', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'has_verified_email' => $user->hasVerifiedEmail(),
        ]);

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('Email is already verified.');
            
            // If user has no profile, redirect to complete profile
            if (!$user->profile) {
                return redirect()->route('profile.complete')->with('success', 'Your email is already verified. Please complete your profile.');
            }
            
            // Otherwise redirect to home
            return redirect()->route('home')->with('success', 'Your email is already verified.');
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            Log::info('Email marked as verified successfully.');
            
            // Fire the verified event
            event(new Verified($user));
            
            // Refresh the user model to get updated data
            $user->refresh();
            
            Log::info('User email verification status after verification:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'has_verified_email' => $user->hasVerifiedEmail(),
            ]);
            
            // Check if user has completed profile
            if (!$user->profile) {
                return redirect()->route('profile.complete')->with('success', 'Email verified successfully! Please complete your profile.');
            }
            
            // User has profile, redirect to dashboard
            return redirect()->route('home')->with('success', 'Email verified successfully!');
            
        } else {
            Log::error('Failed to mark email as verified.');
            return redirect()->route('verification.notice')->with('error', 'Failed to verify email. Please try again.');
        }
    }
}