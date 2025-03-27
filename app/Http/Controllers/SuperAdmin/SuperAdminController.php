<?php

namespace App\Http\Controllers\SuperAdmin;


use App\Models\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Farmers\CropFarmer;
use App\Models\Farmers\AnimalFarmer;
use App\Models\Farmers\AbattoirOperator;
use App\Models\Farmers\Processor;
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

    //Analytics functions

    public function analytics(Request $request)
    {
        // Total Practitioners
        $totalPractitioners = User::whereHas('profile')->where('role', '!=', 'admin')->count();

        // Gender Breakdown
        $genderBreakdown = Profile::select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender');

        // Practice Distribution
        $practiceDistribution = [
            'Crop Farmers' => CropFarmer::count(),
            'Animal Farmers' => AnimalFarmer::count(),
            'Abattoir Operators' => AbattoirOperator::count(),
            'Processors' => Processor::count(),
        ];

        // LGA Distribution
        $lgaDistribution = Profile::select('lga', DB::raw('count(*) as count'))
            ->groupBy('lga')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Age Groups
        $ageGroups = Profile::whereNotNull('dob')
            ->selectRaw('
                CASE 
                    WHEN FLOOR(DATEDIFF(CURDATE(), dob)/365) BETWEEN 18 AND 25 THEN "18-25"
                    WHEN FLOOR(DATEDIFF(CURDATE(), dob)/365) BETWEEN 26 AND 35 THEN "26-35"
                    WHEN FLOOR(DATEDIFF(CURDATE(), dob)/365) BETWEEN 36 AND 45 THEN "36-45"
                    WHEN FLOOR(DATEDIFF(CURDATE(), dob)/365) BETWEEN 46 AND 60 THEN "46-60"
                    ELSE "60+"
                END as age_group,
                count(*) as count
            ')
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        // Income Levels
        $incomeLevels = Profile::select('income_level', DB::raw('count(*) as count'))
            ->groupBy('income_level')
            ->get();

        // Filter Options
        $practice = $request->input('practice', 'crop');
        $filter = $request->input('filter');
        $lgaFilter = $request->input('lga');
        $genderFilter = $request->input('gender');

        // Available LGAs for Filter
        $lgas = Profile::select('lga')->distinct()->pluck('lga')->sort();

        $reportData = [];
        $reportTitle = '';
        $practiceOptions = [
            'crop' => 'Crop Farmers',
            'animal' => 'Animal Farmers',
            'abattoir' => 'Abattoir Operators',
            'processor' => 'Processors',
        ];

        // Practice-Specific Chart Data
        $chartData = ['labels' => [], 'counts' => []];

        switch ($practice) {
            case 'crop':
                $query = CropFarmer::with('user.profile')->where('status', 'approved');
                if ($filter) $query->where('crop', $filter);
                if ($lgaFilter) $query->whereHas('user.profile', fn($q) => $q->where('lga', $lgaFilter));
                if ($genderFilter) $query->whereHas('user.profile', fn($q) => $q->where('gender', $genderFilter));
                $reportTitle = 'Crop Farmers Report' . ($filter ? " - $filter" : '');
                $reportData = $query->get()->map(function ($farmer) {
                    $farmer->age = $farmer->user->profile->dob ? Carbon::parse($farmer->user->profile->dob)->age : null;
                    $farmer->key_metric = $farmer->crop;
                    $farmer->scale_metric = $farmer->farm_size;
                    return $farmer;
                });
                $chartData = CropFarmer::select('crop', DB::raw('count(*) as count'))
                    ->where('status', 'approved')
                    ->groupBy('crop')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->get()
                    ->pluck('count', 'crop')
                    ->all();
                break;

            case 'animal':
                $query = AnimalFarmer::with('user.profile')->where('status', 'approved');
                if ($filter) $query->where('livestock', $filter);
                if ($lgaFilter) $query->whereHas('user.profile', fn($q) => $q->where('lga', $lgaFilter));
                if ($genderFilter) $query->whereHas('user.profile', fn($q) => $q->where('gender', $genderFilter));
                $reportTitle = 'Animal Farmers Report' . ($filter ? " - $filter" : '');
                $reportData = $query->get()->map(function ($farmer) {
                    $farmer->age = $farmer->user->profile->dob ? Carbon::parse($farmer->user->profile->dob)->age : null;
                    $farmer->key_metric = $farmer->livestock;
                    $farmer->scale_metric = $farmer->herd_size;
                    return $farmer;
                });
                $chartData = AnimalFarmer::select('livestock', DB::raw('count(*) as count'))
                    ->where('status', 'approved')
                    ->groupBy('livestock')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->get()
                    ->pluck('count', 'livestock')
                    ->all();
                break;

            case 'abattoir':
                $query = AbattoirOperator::with('user.profile')->where('status', 'approved');
                if ($filter) $query->where('operational_capacity', $filter);
                if ($lgaFilter) $query->whereHas('user.profile', fn($q) => $q->where('lga', $lgaFilter));
                if ($genderFilter) $query->whereHas('user.profile', fn($q) => $q->where('gender', $genderFilter));
                $reportTitle = 'Abattoir Operators Report' . ($filter ? " - $filter Capacity" : '');
                $reportData = $query->get()->map(function ($operator) {
                    $operator->age = $operator->user->profile->dob ? Carbon::parse($operator->user->profile->dob)->age : null;
                    $operator->key_metric = $operator->facility_type;
                    $operator->scale_metric = $operator->operational_capacity;
                    return $operator;
                });
                $chartData = AbattoirOperator::select('facility_type', DB::raw('count(*) as count'))
                    ->where('status', 'approved')
                    ->groupBy('facility_type')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->get()
                    ->pluck('count', 'facility_type')
                    ->all();
                break;

            case 'processor':
                $query = Processor::with('user.profile')->where('status', 'approved');
                if ($filter) $query->whereJsonContains('processed_items', $filter);
                if ($lgaFilter) $query->whereHas('user.profile', fn($q) => $q->where('lga', $lgaFilter));
                if ($genderFilter) $query->whereHas('user.profile', fn($q) => $q->where('gender', $genderFilter));
                $reportTitle = 'Processors Report' . ($filter ? " - $filter" : '');
                $reportData = $query->get()->map(function ($processor) {
                    $processor->age = $processor->user->profile->dob ? Carbon::parse($processor->user->profile->dob)->age : null;
                    $processor->key_metric = json_decode($processor->processed_items, true) ? implode(', ', json_decode($processor->processed_items, true)) : 'N/A';
                    $processor->scale_metric = $processor->processing_capacity;
                    return $processor;
                });
                $chartData = Processor::select(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(processed_items, "$[0]")) as item'), DB::raw('count(*) as count'))
                    ->where('status', 'approved')
                    ->groupBy('item')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->get()
                    ->pluck('count', 'item')
                    ->all();
                break;
        }

        return view('super_admin.analytics', compact(
            'totalPractitioners',
            'genderBreakdown',
            'practiceDistribution',
            'lgaDistribution',
            'ageGroups',
            'incomeLevels',
            'reportData',
            'reportTitle',
            'practice',
            'filter',
            'lgaFilter',
            'genderFilter',
            'practiceOptions',
            'lgas',
            'chartData'
        ));
    }

    
}