<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the farmer dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;
        
        // If farmer hasn't changed password, redirect to force change
        if ($farmer && !$farmer->password_changed) {
            return redirect()->route('password.force_change');
        }

        return view('user.farmer-dashboard', [
            'farmer' => $farmer,
            'user' => $user
        ]);
    }

    /**
     * Display farmer profile
     */
    public function profile()
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        return view('user.farmer-profile', [
            'farmer' => $farmer,
            'user' => $user
        ]);
    }

    /**
     * Update farmer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmerProfile;

        $validated = $request->validate([
            'phone_primary' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'residential_address' => 'required|string|max:255',
            'educational_level' => 'required|string',
            'household_size' => 'required|integer|min:1',
            'primary_occupation' => 'required|string',
        ]);

        $farmer->update($validated);

        return redirect()->route('farmer.profile')
            ->with('success', 'Profile updated successfully!');
    }
}