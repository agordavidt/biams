<?php


namespace App\Http\Controllers\EnrollmentAgent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = auth()->user();
        
        // Get statistics for the agent's LGA
        $stats = [
            'total_farmers' => 0, 
            'verified_farmers' => 0,
            'pending_verification' => 0,
            'this_month_enrollments' => 0,
        ];
        
        return view('enrollment_agent.dashboard', compact('stats', 'agent'));
    }
}