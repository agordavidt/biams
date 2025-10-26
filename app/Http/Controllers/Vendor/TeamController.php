<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'No vendor account found.');
        }

        $teamMembers = $vendor->users()
            ->with('roles')
            ->latest()
            ->get();

        return view('vendor.team.index', compact('vendor', 'teamMembers'));
    }

    public function create()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'No vendor account found.');
        }

        return view('vendor.team.create', compact('vendor'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'No vendor account found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Vendor Manager,Distribution Agent',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $role = Role::where('name', $request->role)->first();

            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'vendor_id' => $vendor->id,
                'status' => 'onboarded',
                'email_verified_at' => now(),
            ]);

            $newUser->assignRole($role);

            DB::commit();

            return redirect()->route('vendor.team.index')
                ->with('success', "Team member added successfully as {$request->role}.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error adding team member: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $teamMember)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure the team member belongs to this vendor
        if ($teamMember->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.team.index')
                ->with('error', 'Unauthorized access.');
        }

        return view('vendor.team.edit', compact('vendor', 'teamMember'));
    }

    public function update(Request $request, User $teamMember)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure the team member belongs to this vendor
        if ($teamMember->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.team.index')
                ->with('error', 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $teamMember->id,
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:Vendor Manager,Distribution Agent',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $teamMember->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

            // Update role if changed
            $currentRole = $teamMember->roles->first()->name;
            if ($currentRole !== $request->role) {
                $teamMember->syncRoles([$request->role]);
            }

            DB::commit();

            return redirect()->route('vendor.team.index')
                ->with('success', 'Team member updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating team member: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $teamMember)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure the team member belongs to this vendor
        if ($teamMember->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.team.index')
                ->with('error', 'Unauthorized access.');
        }

        // Prevent deleting yourself
        if ($teamMember->id === $user->id) {
            return redirect()->route('vendor.team.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Ensure at least one Vendor Manager remains
        $vendorManagersCount = $vendor->vendorManager()->count();
        if ($teamMember->hasRole('Vendor Manager') && $vendorManagersCount <= 1) {
            return redirect()->route('vendor.team.index')
                ->with('error', 'Cannot delete the last Vendor Manager. Add another manager first.');
        }

        try {
            $teamMember->delete();

            return redirect()->route('vendor.team.index')
                ->with('success', 'Team member removed successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error removing team member: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, User $teamMember)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Ensure the team member belongs to this vendor
        if ($teamMember->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.team.index')
                ->with('error', 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $teamMember->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('vendor.team.index')
                ->with('success', 'Password reset successfully for ' . $teamMember->name);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error resetting password: ' . $e->getMessage());
        }
    }
}