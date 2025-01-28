<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserOnboardedNotification;
use App\Notifications\CustomNotification;

use App\Models\Registration;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        
        $users = User::all();
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





        // Show all registrations for review
        public function showRegistrations()
        {
            $registrations = Registration::with(['user', 'practice'])->get();
            return view('admin.registrations.index', compact('registrations'));
        }

        // Approve or reject a registration
        public function updateRegistrationStatus(Request $request, $registration_id)
        {
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);

            $registration = Registration::findOrFail($registration_id);
            $registration->update(['status' => $request->status]);

            return redirect()->back()->with('success', 'Registration status updated successfully!');
        }

}






