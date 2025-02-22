<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // Super Admin Dashboard
    public function dashboard()
    {
        return view('super_admin.dashboard');
    }

    // List all users (admins and regular users)
    public function manageUsers()
    {
        $users = User::all();
        return view('super_admin.users.index', compact('users'));
    }

    // Show the form to create a new user (admin or regular user)
    public function createUser()
    {
        return view('super_admin.users.create');
    }

    // Store a new user
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:pending,onboarded',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('super_admin.users')->with('success', 'User created successfully.');
    }

    // Show the form to edit a user
    public function editUser(User $user)
    {
        return view('super_admin.users.edit', compact('user'));
    }

    // Update a user
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'status' => 'required|in:pending,onboarded',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('super_admin.users')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('super_admin.users')->with('success', 'User deleted successfully.');
    }
}




