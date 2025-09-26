<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserOnboardedNotification;
use App\Notifications\CustomNotification;
use App\Models\Farmers\CropFarmer;
use App\Models\Farmers\AnimalFarmer;
use App\Models\Farmers\AbattoirOperator;
use App\Models\Farmers\Processor;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Only count users with role 'user'
        $totalUsers = User::where('role', 'user')->count();        
        $newToday = User::where('role', 'user')->whereDate('created_at', Carbon::today())->count();        
        $pendingUsers = User::where('role', 'user')->where('status', 'pending')->count();        
        
        $completedProfiles = Profile::whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->whereNotNull('phone')
            ->whereNotNull('gender')
            ->whereNotNull('dob')
            ->whereNotNull('lga')
            ->count();
            
        $profileCompletion = [
            'percentage' => $totalUsers > 0 ? round(($completedProfiles / $totalUsers) * 100) : 0,
            'completed' => $completedProfiles
        ];
        
        $stats = [
            'totalUsers' => $totalUsers,
            'newToday' => $newToday,
            'pendingUsers' => $pendingUsers,
            'profileCompletion' => $profileCompletion,
            'recentActivity' => User::where('role', 'user')->where('email_verified_at', '>=', Carbon::now()->subDay())->count()
        ];
        
        // Fixed monthly stats query - only for users with role 'user'
        $monthlyStats = User::select(
            DB::raw('DATE_FORMAT(created_at, "%b") as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month_num'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "onboarded" THEN 1 ELSE 0 END) as onboarded')
        )
            ->where('role', 'user')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month', 'year', 'month_num')
            ->orderBy('year', 'asc')
            ->orderBy('month_num', 'asc')
            ->get();
            
        $practiceDistribution = [
            'Crop Farmers' => CropFarmer::whereHas('user', function($query) {
                $query->where('role', 'user');
            })->count(),
            'Animal Farmers' => AnimalFarmer::whereHas('user', function($query) {
                $query->where('role', 'user');
            })->count(),
            'Abattoir Operators' => AbattoirOperator::whereHas('user', function($query) {
                $query->where('role', 'user');
            })->count(),
            'Processors' => Processor::whereHas('user', function($query) {
                $query->where('role', 'user');
            })->count()
        ];
        
        $recentUsers = User::with('profile')
            ->where('role', 'user')
            ->select('users.*')
            ->selectRaw('
                CASE 
                    WHEN status = "onboarded" THEN "success"
                    WHEN status = "pending" THEN "warning"
                    WHEN status = "rejected" THEN "danger"
                    ELSE "secondary"
                END as status_color
            ')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'lga' => $user->profile->lga ?? 'N/A',
                    'status' => ucfirst($user->status),
                    'status_color' => $user->status_color,
                    'phone' => $user->profile->phone ?? 'N/A',
                    'gender' => $user->profile->gender ?? 'N/A',
                    'age' => $user->profile?->dob ? Carbon::parse($user->profile?->dob)->age : 'N/A',
                ];
            });
            
        $regionalDistribution = Profile::select('lga', DB::raw('COUNT(*) as count'))
            ->whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->whereNotNull('lga')
            ->groupBy('lga')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('admin.index', compact(
            'stats',
            'monthlyStats',
            'practiceDistribution',
            'recentUsers',
            'regionalDistribution'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in the database.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully!');
    }

    /**
     * Onboard a user (approve their profile).
     */
    public function onboardUser(User $user)
    {
        // Update user status to "onboarded"
        $user->update(['status' => 'onboarded']);

        // Send notification to the user
        try {
            $user->notify(new UserOnboardedNotification());
        } catch (\Exception $e) {
            \Log::error('Notification failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'notification' => UserOnboardedNotification::class,
            ]);
        }

        return redirect()->back()->with('success', 'User onboarded successfully!');
    }

    /**
     * Reject a user (reject their profile and deactivate account)
     * Best practice: Mark as rejected rather than delete to maintain audit trail
     */
    public function rejectUser(Request $request, User $user)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Update user status to "rejected" - best practice for audit trail
        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->comment,
            'rejected_at' => now()
        ]);   

        return redirect()->back()->with('success', 'User rejected successfully! Account has been deactivated.');
    }

    /**
     * Display a summary of users with pagination and search.
     */
    public function userSummary(Request $request)
    {
        $query = User::with('profile')->where('role', 'user');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('profile', function($profileQuery) use ($search) {
                      $profileQuery->where('phone', 'LIKE', "%{$search}%")
                                  ->orWhere('lga', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Pagination - 15 users per page
        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Count statistics for non-admin users
        $totalUsers = User::where('role', 'user')->count();
        $pendingUsers = User::where('role', 'user')->where('status', 'pending')->count();
        $approvedUsers = User::where('role', 'user')->where('status', 'onboarded')->count();
        $rejectedUsers = User::where('role', 'user')->where('status', 'rejected')->count();

        return view('admin.users.summary', compact(
            'users', 
            'totalUsers', 
            'pendingUsers', 
            'approvedUsers', 
            'rejectedUsers'
        ));
    }

    /**
     * Get user details for modal view
     */
    public function getUserDetails(User $user)
    {
        // Ensure we only show details for users with role 'user'
        if ($user->role !== 'user') {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->load('profile');
        
        $userDetails = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : null,
            'created_at' => $user->created_at->format('M d, Y H:i'),
            'updated_at' => $user->updated_at->format('M d, Y H:i'),
            'profile' => [
                'phone' => $user->profile->phone ?? 'N/A',
                'dob' => $user->profile->dob ?? 'N/A',
                'age' => $user->profile?->dob ? Carbon::parse($user->profile->dob)->age : 'N/A',
                'gender' => $user->profile->gender ?? 'N/A',
                'nin' => $user->profile->nin ?? 'N/A',
                'education' => $user->profile->education ?? 'N/A',
                'household_size' => $user->profile->household_size ?? 'N/A',
                'dependents' => $user->profile->dependents ?? 'N/A',
                'income_level' => $user->profile->income_level ?? 'N/A',
                'lga' => $user->profile->lga ?? 'N/A',
                'address' => $user->profile->address ?? 'N/A',
            ],
            'rejection_reason' => $user->rejection_reason ?? null,
            'rejected_at' => $user->rejected_at ? Carbon::parse($user->rejected_at)->format('M d, Y H:i') : null,
        ];

        return response()->json($userDetails);
    }

    /**
     * Send a notification to a user.
     */
    public function sendNotification(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Send notification to the user
        try {
            $user->notify(new CustomNotification($request->message));
        } catch (\Exception $e) {
            \Log::error('Notification failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'notification' => CustomNotification::class,
            ]);
        }

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }

    /**
     * Permanently delete a user account (use with caution)
     * Best practice: Only allow this for specific cases, prefer soft deletion
     */
    public function deleteUser(User $user)
    {
        // Only allow deletion of users with role 'user'
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        // Delete the associated profile if it exists
        if ($user->profile) {
            $user->profile->delete();
        }

        // Delete related records
        $user->cropFarmers()->delete();
        $user->animalFarmers()->delete();
        $user->abattoirOperators()->delete();
        $user->processors()->delete();
        $user->marketplaceListings()->delete();
        $user->sentMessages()->delete();
        $user->receivedMessages()->delete();

        // Delete the user account
        $user->delete();

        return redirect()->back()->with('success', 'User account deleted permanently!');
    }

    // ... rest of the agricultural practices methods remain the same
    public function cropFarmers()
    {
        $applications = CropFarmer::with(['user' => function ($query) {
            $query->with('profile')->where('role', 'user');
        }])->get();
        $type = 'crop-farmer';
        return view('admin.practices.crop-farmers', compact('applications', 'type'));
    }

    public function animalFarmers()
    {
        $applications = AnimalFarmer::with(['user' => function ($query) {
            $query->with('profile')->where('role', 'user');
        }])->get();
        $type = 'animal-farmer';
        return view('admin.practices.animal-farmers', compact('applications', 'type'));
    }

    public function abattoirOperators()
    {
        $applications = AbattoirOperator::with(['user' => function ($query) {
            $query->with('profile')->where('role', 'user');
        }])->get();
        $type = 'abattoir-operator';
        return view('admin.practices.abattoir-operators', compact('applications', 'type'));
    }

    public function processors()
    {
        $applications = Processor::with(['user' => function ($query) {
            $query->with('profile')->where('role', 'user');
        }])->get();
        $type = 'processor';
        return view('admin.practices.processors', compact('applications', 'type'));
    }

    /**
     * Approve an agricultural practice application
     */
    public function approve($type, $id)
    {
        try {
            // Determine the model based on the type
            switch ($type) {
                case 'crop-farmer':
                    $model = CropFarmer::findOrFail($id);
                    break;
                case 'animal-farmer':
                    $model = AnimalFarmer::findOrFail($id);
                    break;
                case 'abattoir-operator':
                    $model = AbattoirOperator::findOrFail($id);
                    break;
                case 'processor':
                    $model = Processor::findOrFail($id);
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid application type.');
            }

            // Update the status
            $model->update(['status' => 'approved']);

            // Send approval notification
            $model->user->notify(new ApplicationStatusUpdated('approved'));

            return redirect()->back()->with('success', 'Application approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error approving application: ' . $e->getMessage());
        }
    }

    /**
     * Reject an agricultural practice application with comments
     */
    public function reject(Request $request, $type, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'rejection_comments' => 'required|string|max:1000',
            ]);

            // Determine the model based on the type
            switch ($type) {
                case 'crop-farmer':
                    $model = CropFarmer::findOrFail($id);
                    break;
                case 'animal-farmer':
                    $model = AnimalFarmer::findOrFail($id);
                    break;
                case 'abattoir-operator':
                    $model = AbattoirOperator::findOrFail($id);
                    break;
                case 'processor':
                    $model = Processor::findOrFail($id);
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid application type.');
            }

            // Update the status and comments
            $model->update([
                'status' => 'rejected',
                'rejection_comments' => $validated['rejection_comments']
            ]);

            // Send rejection notification with comments
            $model->user->notify(new ApplicationStatusUpdated('rejected', $validated['rejection_comments']));

            return redirect()->back()->with('success', 'Application rejected successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error rejecting application: ' . $e->getMessage());
        }
    }
}