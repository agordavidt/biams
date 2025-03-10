<?php

namespace App\Http\Controllers\SuperAdmin;


use App\Models\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use App\Models\ActivityLog;
use App\Models\Content;
use App\Models\Integration;
use App\Models\ErrorLog;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    // ==================== Dashboard ====================
    public function dashboard()
    {
        // Total Users
        $totalUsers = User::count();

        // Male and Female Users
        $maleUsers = User::whereHas('profile', function ($query) {
            $query->where('gender', 'Male');
        })->count();
        $femaleUsers = User::whereHas('profile', function ($query) {
            $query->where('gender', 'Female');
        })->count();

        // Pending Users
        $pendingUsers = User::where('status', 'pending')->count();

        // Registration Trends (Last 12 Months)
        $registrationTrends = User::selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count');
        $registrationMonths = User::selectRaw('DATE_FORMAT(created_at, "%b %Y") as month')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('month');

        // User Distribution by LGA
        $lgaDistribution = Profile::select('lga', DB::raw('COUNT(*) as count'))
            ->whereNotNull('lga')
            ->groupBy('lga')
            ->orderByDesc('count')
            ->pluck('count');
        $lgaCategories = Profile::select('lga')
            ->whereNotNull('lga')
            ->groupBy('lga')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->pluck('lga');

        // Recent Users
        $recentUsers = User::with('profile')->latest()->take(10)->get();

        return view('super_admin.dashboard', compact(
            'totalUsers',
            'maleUsers',
            'femaleUsers',
            'pendingUsers',
            'registrationTrends',
            'registrationMonths',
            'lgaDistribution',
            'lgaCategories',
            'recentUsers'
        ));
    }

    // ==================== User and Role Management ====================
    public function manageUsers()
    {
        $users = User::with('roles')->latest()->get();
        return view('super_admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('super_admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->role);

        return redirect()->route('super_admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('super_admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->roles()->sync($request->role);

        return redirect()->route('super_admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('super_admin.users')->with('success', 'User deleted successfully.');
    }

    // ==================== System Configuration ====================
    public function manageSettings()
    {
        $settings = Setting::all();
        return view('super_admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('super_admin.settings')->with('success', 'Settings updated successfully.');
    }

    // ==================== Security and Access Control ====================
    public function activityLogs()
    {
        $logs = ActivityLog::latest()->paginate(20);
        return view('super_admin.security.activity_logs', compact('logs'));
    }

    public function forcePasswordReset(User $user)
    {
        $user->update(['password_reset_required' => true]);
        return redirect()->back()->with('success', 'Password reset enforced.');
    }

    // ==================== Content and Data Management ====================
    public function manageContent()
    {
        $contents = Content::latest()->get();
        return view('super_admin.content.index', compact('contents'));
    }

    public function storeContent(Request $request)
    {
        $request->validate(['title' => 'required', 'body' => 'required']);
        Content::create($request->all());
        return redirect()->route('super_admin.content')->with('success', 'Content created successfully.');
    }

    public function updateContent(Request $request, Content $content)
    {
        $request->validate(['title' => 'required', 'body' => 'required']);
        $content->update($request->all());
        return redirect()->route('super_admin.content')->with('success', 'Content updated successfully.');
    }

    public function deleteContent(Content $content)
    {
        $content->delete();
        return redirect()->route('super_admin.content')->with('success', 'Content deleted successfully.');
    }

    // ==================== Integration and Third-Party Services ====================
    public function manageIntegrations()
    {
        $integrations = Integration::latest()->get();
        return view('super_admin.integrations.index', compact('integrations'));
    }

    public function updateIntegration(Request $request, Integration $integration)
    {
        $request->validate(['api_key' => 'required']);
        $integration->update($request->all());
        return redirect()->route('super_admin.integrations')->with('success', 'Integration updated successfully.');
    }

    // ==================== Troubleshooting and Support ====================
    public function errorLogs()
    {
        $logs = ErrorLog::latest()->paginate(20);
        return view('super_admin.support.error_logs', compact('logs'));
    }

    // ==================== Audit and Compliance ====================
    public function auditLogs()
    {
        $logs = AuditLog::latest()->paginate(20);
        return view('super_admin.audit.index', compact('logs'));
    }
}