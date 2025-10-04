<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LGA;
use App\Models\Department;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Base query for staff members (excluding Super Admin, Governor, State Admin)
        $query = User::role(['LGA Admin', 'Enrollment Agent'])
            ->with(['administrativeUnit', 'roles'])
            ->latest();

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Administrative unit filter (improved)
        if ($request->filled('administrative_type') && $request->filled('administrative_id')) {
            $query->where('administrative_type', $request->administrative_type)
                  ->where('administrative_id', $request->administrative_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(20)->withQueryString();

        // Comprehensive Statistics
        $stats = $this->getStatistics();

        // Get all administrative units for filters
        $lgas = LGA::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $agencies = Agency::with('department')->orderBy('name')->get();

        return view('admin.users.index', compact(
            'users', 
            'stats', 
            'lgas',
            'departments',
            'agencies'
        ));
    }

    private function getStatistics()
    {
        // Total staff counts
        $totalStaff = User::role(['LGA Admin', 'Enrollment Agent'])->count();
        $lgaAdmins = User::role('LGA Admin')->count();
        $enrollmentAgents = User::role('Enrollment Agent')->count();
        $totalFarmers = User::role('User')->whereHas('farmerProfile')->count();

        // Staff by status
        $activeStaff = User::role(['LGA Admin', 'Enrollment Agent'])
            ->where('status', 'onboarded')
            ->count();
        
        $pendingStaff = User::role(['LGA Admin', 'Enrollment Agent'])
            ->where('status', 'pending')
            ->count();

        // LGA Distribution with proper eager loading
        $lgaDistribution = LGA::withCount([
            'users as lga_admins_count' => function($query) {
                $query->role('LGA Admin');
            },
            'users as agents_count' => function($query) {
                $query->role('Enrollment Agent');
            },
            'users as total_staff_count' => function($query) {
                $query->role(['LGA Admin', 'Enrollment Agent']);
            }
        ])
        ->having('total_staff_count', '>', 0)
        ->orderBy('name')
        ->get();

        // Department Distribution
        $deptDistribution = Department::withCount([
            'users as staff_count' => function($query) {
                $query->role(['LGA Admin', 'Enrollment Agent', 'State Admin']);
            }
        ])
        ->having('staff_count', '>', 0)
        ->orderBy('name')
        ->get();

        // Recent additions (last 30 days)
        $recentStaff = User::role(['LGA Admin', 'Enrollment Agent'])
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return [
            'totalStaff' => $totalStaff,
            'lgaAdmins' => $lgaAdmins,
            'enrollmentAgents' => $enrollmentAgents,
            'totalFarmers' => $totalFarmers,
            'activeStaff' => $activeStaff,
            'pendingStaff' => $pendingStaff,
            'recentStaff' => $recentStaff,
            'lgaDistribution' => $lgaDistribution,
            'deptDistribution' => $deptDistribution,
            'lgasCovered' => $lgaDistribution->count(),
            'deptsCovered' => $deptDistribution->count(),
        ];
    }

    public function show(User $user)
    {
        // Load relationships
        $user->load(['administrativeUnit', 'roles', 'permissions']);
        
        // Get activity statistics for this user
        $stats = [
            'farmers_enrolled' => 0, // You can add this based on your Farmer model
            'account_age_days' => $user->created_at->diffInDays(now()),
            'last_login' => $user->last_login_at ?? null, // If you track this
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }
}