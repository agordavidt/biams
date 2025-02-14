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
        // Get total users count
        $totalUsers = User::count();
        
        // Get new users today
        $newToday = User::whereDate('created_at', Carbon::today())->count();
        
        // Get pending users
        $pendingUsers = User::where('status', 'pending')->count();
        
        // Calculate profile completion
        $completedProfiles = Profile::whereNotNull('phone')
            ->whereNotNull('gender')
            ->whereNotNull('dob')
            ->whereNotNull('lga')
            ->count();
            
        $profileCompletion = [
            'percentage' => $totalUsers > 0 ? round(($completedProfiles / $totalUsers) * 100) : 0,
            'completed' => $completedProfiles
        ];
        
        // Compile stats array
        $stats = [
            'totalUsers' => $totalUsers,
            'newToday' => $newToday,
            'pendingUsers' => $pendingUsers,
            'profileCompletion' => $profileCompletion,
            'recentActivity' => User::where('email_verified_at', '>=', Carbon::now()->subDay())->count()
        ];
        
        // Get monthly registration stats for the past 12 months
        $monthlyStats = User::select(
            DB::raw('DATE_FORMAT(created_at, "%b") as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as onboarded')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('created_at')
            ->get();
            
            
        // Get practice distribution
        $practiceDistribution = [
            'Crop Farmers' => CropFarmer::count(),
            'Animal Farmers' => AnimalFarmer::count(),
            'Abattoir Operators' => AbattoirOperator::count(),
            'Processors' => Processor::count()
        ];
        
        // Get recent users with their profiles
        $recentUsers = User::with('profile')
            ->select('users.*')
            ->selectRaw('
                CASE 
                    WHEN status = "active" THEN "success"
                    WHEN status = "pending" THEN "warning"
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
            
        // Get regional distribution (top LGAs)
        $regionalDistribution = Profile::select('lga', DB::raw('COUNT(*) as count'))
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
        $user->notify(new UserOnboardedNotification());

        return redirect()->back()->with('success', 'User onboarded successfully!');
    }


    /**
     * Reject a user (reject their profile)
     **/
    public function rejectUser(Request $request, User $user)
{
    $request->validate([
        'comment' => 'required|string',
    ]);

    // Update user status to "rejected"
    $user->update(['status' => 'rejected']);   

    return redirect()->back()->with('success', 'User rejected successfully!');
}
    /**
     * Display a summary of users.
     */
   public function userSummary()
{
    // Fetch users and their profiles, excluding 'admin' roles
    $users = User::with('profile')
        ->where('role', '!=', 'admin')
        ->get();

    // Count statistics for non-admin users
    $totalUsers = $users->count();
    $pendingUsers = $users->where('status', 'pending')->count();
    $approvedUsers = $users->where('status', 'onboarded')->count();

    // Pass users and summary statistics to the view
    return view('admin.users.summary', compact('users', 'totalUsers', 'pendingUsers', 'approvedUsers'));
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
        $user->notify(new CustomNotification($request->message));

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }


    /**
         * Delete a user account.
         */
        public function deleteUser(User $user)
        {
            // Delete the associated profile if it exists
            if ($user->profile) {
                $user->profile->delete();
            }

            // Delete the user account
            $user->delete();

            return redirect()->back()->with('success', 'User account deleted successfully!');
        }



        /**
         * Agricultural Practices
         */
       
            public function cropFarmers()
            {
                // $applications = CropFarmer::with('user')->get();
                $applications = CropFarmer::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                $type = 'crop-farmer'; // Define the type and do same for all the agricultural practices
                return view('admin.practices.crop-farmers', compact('applications', 'type'));
            }

            // Animal Farmers Applications
            public function animalFarmers()
            {
                $applications = AnimalFarmer::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                $type = 'animal-farmer'; 
                return view('admin.practices.animal-farmers', compact('applications', 'type'));
            }

            // Abattoir Operators Applications
            public function abattoirOperators()
            {
                $applications = AbattoirOperator::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                return view('admin.practices.abattoir-operators', compact('applications'));
            }

            // Processors Applications
            public function processors()
            {
                $applications = Processor::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                return view('admin.practices.processors', compact('applications'));
            }

            // Approve an application
            public function approve($type, $id)
            {
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

                // Send approval notification (email or dashboard)
                $model->user->notify(new ApplicationStatusUpdated('approved'));

                return redirect()->back()->with('success', 'Application approved successfully.');
            }

            // Reject an application
            public function reject($type, $id)
            {
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
                $model->update(['status' => 'rejected']);

                // Send rejection notification (email or dashboard)
                $model->user->notify(new ApplicationStatusUpdated('rejected'));

                return redirect()->back()->with('success', 'Application rejected successfully.');
            }      


}




