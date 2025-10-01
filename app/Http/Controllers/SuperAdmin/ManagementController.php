<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Agency;
use App\Models\LGA;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManagementController extends Controller
{


    public function index()
    {
        return view('super_admin.management.index');
    }

     /*

    |--------------------------------------------------------------------------

    | User Management

    |--------------------------------------------------------------------------

    */

    public function users()
    {
        $users = User::with(['roles', 'administrativeUnit'])->get();
        return view('super_admin.management.users.index', compact('users'));
    }

    public function createUser()
    {
        $globalRoles = ['Super Admin', 'Governor'];        
        $unitRoles = Role::whereNotIn('name', $globalRoles)->pluck('name', 'id');
        $globalRoles = Role::whereIn('name', $globalRoles)->pluck('name', 'id');
        $roles = $globalRoles->merge($unitRoles);
        $departments = Department::all();
        $agencies = Agency::all();
        $lgas = LGA::all();

        return view('super_admin.management.users.create', compact('roles', 'unitRoles', 'departments', 'agencies', 'lgas'));
    }

    public function storeUser(Request $request)
    {
        // 🚨 UPDATE 1: Removed 'status' from validation. The status will be set to 'pending' by default.
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
            'administrative_type' => ['nullable', 'string', Rule::in(['Department', 'Agency', 'LGA'])],
            'administrative_id' => 'nullable|integer',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // ✅ UPDATE 2: Hardcode status to 'pending' on creation.
            'status' => 'pending', 
        ]);

        $role = Role::findById($data['role_id']);
        $user->assignRole($role);

        if (!empty($data['administrative_type']) && !empty($data['administrative_id'])) {
            $user->administrative_type = "App\\Models\\{$data['administrative_type']}";
            $user->administrative_id = $data['administrative_id'];
            $user->save();
        }

        // 💡 Recommendation: Update success message to reflect the pending status.
        return redirect()->route('super_admin.management.users.index')->with('success', 'User created successfully and is currently **pending** approval.');
    }


    public function editUser(User $user)
    {
        $globalRoles = ['Super Admin', 'Governor'];        
        $unitRoles = Role::whereNotIn('name', $globalRoles)->pluck('name', 'id');
        $globalRoles = Role::whereIn('name', $globalRoles)->pluck('name', 'id');
        $roles = $globalRoles->merge($unitRoles);
        $departments = Department::all();
        $agencies = Agency::all();
        $lgas = LGA::all();

        return view('super_admin.management.users.edit', compact('user', 'roles', 'unitRoles', 'departments', 'agencies', 'lgas'));
    }

    public function updateUser(Request $request, User $user)
    {
        //
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
            'administrative_type' => ['nullable', 'string', Rule::in(['Department', 'Agency', 'LGA'])],
            'administrative_id' => 'nullable|integer',
            // ✅ 'status' is REQUIRED for updates
            'status' => ['required', Rule::in(['onboarded', 'pending', 'rejected'])], 
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        // ✅ Status is updated from the edit form.
        $user->status = $data['status']; 
        
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $role = Role::findById($data['role_id']);
        $user->syncRoles([$role]);

        if (!empty($data['administrative_type']) && !empty($data['administrative_id'])) {
            $user->administrative_type = "App\\Models\\{$data['administrative_type']}";
            $user->administrative_id = $data['administrative_id'];
        } else {
            $user->administrative_type = null;
            $user->administrative_id = null;
        }
        
        $user->save();

        return redirect()->route('super_admin.management.users.index')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        // FIX: Changed route from 'super_admin.management.users' to 'super_admin.management.users.index'
        return redirect()->route('super_admin.management.users.index')->with('success', 'User deleted successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Department Management
    |--------------------------------------------------------------------------
    */

    public function departments()
    {
        $departments = Department::withCount('users')->get();
        return view('super_admin.management.departments.index', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:departments,name|max:255',
            'abbreviation' => 'nullable|string|max:50',
        ]);

        Department::create($data);

        return response()->json(['success' => true, 'message' => 'Department created successfully.']);
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($department->id)],
            'abbreviation' => 'nullable|string|max:50',
        ]);

        $department->update($data);

        return response()->json(['success' => true, 'message' => 'Department updated successfully.']);
    }

    public function destroyDepartment(Department $department)
    {
        if ($department->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete department with assigned users.'], 422);
        }

        $department->delete();
        return response()->json(['success' => true, 'message' => 'Department deleted successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | Agency Management
    |--------------------------------------------------------------------------
    */

    public function agencies()
    {
        $agencies = Agency::with('department')->withCount('users')->get();
        $departments = Department::all();
        return view('super_admin.management.agencies.index', compact('agencies', 'departments'));
    }

    public function storeAgency(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:agencies,name|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Agency::create($data);

        return response()->json(['success' => true, 'message' => 'Agency created successfully.']);
    }

    public function updateAgency(Request $request, Agency $agency)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('agencies', 'name')->ignore($agency->id)],
            'department_id' => 'required|exists:departments,id',
        ]);

        $agency->update($data);

        return response()->json(['success' => true, 'message' => 'Agency updated successfully.']);
    }

    public function destroyAgency(Agency $agency)
    {
        if ($agency->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete agency with assigned users.'], 422);
        }

        $agency->delete();
        return response()->json(['success' => true, 'message' => 'Agency deleted successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | LGA Management
    |--------------------------------------------------------------------------
    */

    public function lgas()
    {
        $lgas = LGA::withCount('users')->get();
        return view('super_admin.management.lgas.index', compact('lgas'));
    }
}