<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DistributionProfileController extends Controller
{
    /**
     * Display the Distribution Agent's profile/settings page
     */
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.distribution.dashboard')
                ->with('error', 'No vendor account found.');
        }

        // Get agent's fulfillment statistics
        $stats = [
            'total_fulfilled' => \App\Models\ResourceApplication::where('fulfilled_by', $user->id)->count(),
            'fulfilled_today' => \App\Models\ResourceApplication::where('fulfilled_by', $user->id)
                ->whereDate('fulfilled_at', today())->count(),
            'fulfilled_this_month' => \App\Models\ResourceApplication::where('fulfilled_by', $user->id)
                ->whereMonth('fulfilled_at', now()->month)->count(),
        ];
        
        return view('vendor.distribution.profile', [
            'user' => $user,
            'vendor' => $vendor,
            'stats' => $stats,
        ]);
    }

    /**
     * Update the Distribution Agent's profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }
}