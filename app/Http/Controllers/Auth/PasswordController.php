<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Show the force password change form for farmers
     */
    public function forceChangePasswordForm()
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;
        
        // Security check - ensure only farmers with unchanged passwords can access this
        if (!$farmer || $farmer->password_changed) {
            return redirect()->route('home');
        }
        
        return view('auth.force-password-change', [
            'farmer' => $farmer
        ]);
    }

    /**
     * Update the initial password for farmers
     */
    public function updateInitialPassword(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;
        
        // Security validation
        if (!$farmer || $farmer->password_changed) {
            return redirect()->route('home');
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verify the initial password matches
        if ($request->current_password !== $farmer->initial_password) {
            throw ValidationException::withMessages([
                'current_password' => 'The provided initial password is incorrect.',
            ]);
        }

        // Update user password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Mark farmer as having changed password
        $farmer->update([
            'password_changed' => true,
            'initial_password' => null // Clear the initial password for security
        ]);

        return redirect()->route('farmer.dashboard')
            ->with('success', 'Password updated successfully! Welcome to your dashboard.');
    }
}