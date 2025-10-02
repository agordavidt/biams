<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Agency;
use App\Models\LGA;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get overview statistics
        $stats = [
            'total_users' => User::count(),
            'total_departments' => Department::count(),
            'total_agencies' => Agency::count(),
            'total_lgas' => LGA::count(),
            'onboarded_users' => User::where('status', 'onboarded')->count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'rejected_users' => User::where('status', 'rejected')->count(),
        ];

        // Get user distribution by role
        $usersByRole = User::select('roles.name', DB::raw('count(*) as total'))
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_type', User::class)
            ->groupBy('roles.name')
            ->get()
            ->pluck('total', 'name')
            ->toArray();

        // Get departments with user counts
        $departmentsWithUsers = Department::withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();

        // Get agencies with user counts
        $agenciesWithUsers = Agency::with('department')
            ->withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();

        // Get LGAs with user counts
        $lgasWithUsers = LGA::withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();

        // Get recent users (last 10)
        $recentUsers = User::with('roles')
            ->latest()
            ->limit(10)
            ->get();

        return view('super_admin.dashboard', compact(
            'stats',
            'usersByRole',
            'departmentsWithUsers',
            'agenciesWithUsers',
            'lgasWithUsers',
            'recentUsers'
        ));
    }
}