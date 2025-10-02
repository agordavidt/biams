<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }


    /**
     * Display the form to force a password change (first login).
     */
    public function forceChangePasswordForm()
    {
        // View requires only the new password fields (current password is known implicitly)
        return view('auth.passwords.force-change');
    }


    /**
     * Handles the forced password change on first login for Farmers.
     */
    public function updateInitialPassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            // Use current_password to verify the system-generated password, 
            // but we call it 'initial_password' in the form context.
            'current_password' => ['required', 'current_password'], 
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            DB::beginTransaction();
            
            // 1. Update the User model password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            // 2. Update the linked Farmer profile for security and workflow
            if ($user->farmerProfile) {
                $user->farmerProfile->clearInitialPassword(); // Method defined in Farmer model
            }

            DB::commit();

            // Log user out to force a fresh session with the new credentials
            auth('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('status', 'Your initial password has been successfully updated. Please log in with your new password.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update initial password.');
        }
    }
}
