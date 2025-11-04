<?php

namespace App\Http\Controllers\EnrollmentAgent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = auth()->user();
        $agentLgaId = $agent->administrativeUnit->id;
        
        // Get statistics for the agent's enrolled farmers
        $stats = [
            'total_farmers' => Farmer::where('enrolled_by', $agent->id)->count(),
            'verified_farmers' => Farmer::where('enrolled_by', $agent->id)
                ->whereIn('status', ['active', 'pending_activation'])
                ->count(),
            'pending_verification' => Farmer::where('enrolled_by', $agent->id)
                ->where('status', 'pending_lga_review')
                ->count(),
            'this_month_enrollments' => Farmer::where('enrolled_by', $agent->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
        
        return view('enrollment_agent.dashboard', compact('stats', 'agent'));
    }
}