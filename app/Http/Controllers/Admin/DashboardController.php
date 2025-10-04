<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmer;
use App\Models\FarmLand;
use App\Models\LGA;
use App\Models\Cooperative;
use Illuminate\Support\Facades\DB; // Add this import

class DashboardController extends Controller
{
    public function index()
    {
        // Core Statistics
        $stats = [
            'totalFarmers' => Farmer::count(),
            'activeFarmers' => Farmer::where('status', 'active')->count(),
            'pendingApproval' => Farmer::where('status', 'pending_lga_review')->count(),
            'totalLGAs' => LGA::count(),
            'totalCooperatives' => Cooperative::count(),
            'totalMembers' => Cooperative::sum('total_member_count'),
            'totalFarmLands' => FarmLand::count(),
            'totalLandSize' => FarmLand::sum('total_size_hectares'),
            'totalStaff' => User::role(['LGA Admin', 'Enrollment Agent'])->count(),
            'lgaAdmins' => User::role('LGA Admin')->count(),
            'enrollmentAgents' => User::role('Enrollment Agent')->count(),
        ];

        // Recent Staff Registrations
        $recentStaff = User::role(['LGA Admin', 'Enrollment Agent'])
            ->with(['administrativeUnit'])
            ->latest()
            ->limit(5)
            ->get();

        // Farm Type Distribution
        $farmTypeDistribution = FarmLand::select('farm_type', DB::raw('count(*) as count'))
            ->groupBy('farm_type')
            ->get()
            ->mapWithKeys(function($item) {
                return [ucfirst($item->farm_type) => $item->count];
            });

        return view('admin.dashboard', compact(
            'stats',
            'recentStaff',
            'farmTypeDistribution'
        ));
    }
}