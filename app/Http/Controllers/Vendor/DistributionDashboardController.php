<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('login')->with('error', 'No vendor account associated with this user.');
        }

        // Basic statistics for distribution agent
        $stats = [
            'assigned_resources' => 0, // Will be implemented in next batch
            'fulfilled_today' => 0,
            'pending_fulfillments' => 0,
        ];

        return view('vendor.distribution.dashboard', compact('vendor', 'stats'));
    }
}