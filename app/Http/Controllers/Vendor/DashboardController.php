<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('login')->with('error', 'No vendor account associated with this user.');
        }

        // Basic statistics for vendor dashboard
        $stats = [
            'total_team_members' => $vendor->users()->count(),
            'active_distribution_agents' => $vendor->distributionAgents()->count(),
            'proposed_resources' => $vendor->resources()->where('status', 'proposed')->count(),
            'active_resources' => $vendor->resources()->where('status', 'active')->count(),
        ];

        return view('vendor.dashboard', compact('vendor', 'stats'));
    }
}