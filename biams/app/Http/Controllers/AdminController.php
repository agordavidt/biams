<?php

namespace App\Http\Controllers;

use App\Models\User;
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

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        
        // $users = User::all();
        $users = User::with('profile')->get();
        $totalUsers = User::count();
        $pendingUsers = User::where('status', 'pending')->count();
        $approvedUsers = User::where('status', 'onboarded')->count();
        return view('admin.index', compact('users', 'totalUsers', 'pendingUsers', 'approvedUsers'));
       
    }

    // public function dashboard()
    // {
    //     $users = User::all();
    //     return view('admin.dashboard', compact('users'));
    // }

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
        // Crop Farmers Applications
            // public function cropFarmers()
            // {
            //     $applications = CropFarmer::with('user')->get();
            //     return view('admin.applications.crop-farmers', compact('applications'));
            // }
            public function cropFarmers()
            {
                // $applications = CropFarmer::with('user')->get();
                $applications = CropFarmer::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                $type = 'crop-farmer'; // Define the type and do same for all the agricultural practices
                return view('admin.applications.crop-farmers', compact('applications', 'type'));
            }

            // Animal Farmers Applications
            public function animalFarmers()
            {
                $applications = AnimalFarmer::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                $type = 'animal-farmer'; 
                return view('admin.applications.animal-farmers', compact('applications', 'type'));
            }

            // Abattoir Operators Applications
            public function abattoirOperators()
            {
                $applications = AbattoirOperator::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                return view('admin.applications.abattoir-operators', compact('applications'));
            }

            // Processors Applications
            public function processors()
            {
                $applications = Processor::with(['user' => function ($query) {
                    $query->with('profile'); // Eager load profile for each user for all the agricultural practices
                }])->get();
                return view('admin.applications.processors', compact('applications'));
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
