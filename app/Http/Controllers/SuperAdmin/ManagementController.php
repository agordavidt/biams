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
        // Define global roles that don't need administrative units
        $globalRoles = ['Super Admin', 'Governor'];
        
        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ];
        
        // Get the selected role to check if it needs administrative unit
        $selectedRole = null;
        if ($request->role_id) {
            $selectedRole = Role::find($request->role_id);
        }
        
        // Add administrative unit validation if role requires it
        if ($selectedRole && !in_array($selectedRole->name, $globalRoles)) {
            $rules['administrative_type'] = [
                'required', 
                'string', 
                Rule::in(['Department', 'Agency', 'LGA'])
            ];
            $rules['administrative_id'] = 'required|integer';
        }
        
        // Validate the request
        $validated = $request->validate($rules, [
            'administrative_type.required' => 'This role requires an administrative unit assignment.',
            'administrative_id.required' => 'Please select a specific administrative unit.',
            'role_id.required' => 'Please select a role for this user.',
        ]);
        
        // Additional validation: Check if administrative unit exists
        if (!empty($validated['administrative_type']) && !empty($validated['administrative_id'])) {
            $modelClass = "App\\Models\\{$validated['administrative_type']}";
            
            if (!class_exists($modelClass)) {
                return back()->withErrors(['administrative_type' => 'Invalid administrative type selected.'])->withInput();
            }
            
            $unitExists = $modelClass::where('id', $validated['administrative_id'])->exists();
            
            if (!$unitExists) {
                return back()->withErrors([
                    'administrative_id' => "The selected {$validated['administrative_type']} does not exist."
                ])->withInput();
            }
            
            // Validate role-unit compatibility
            $roleUnitMap = [
                'LGA Admin' => ['LGA'],
                'Enrollment Agent' => ['LGA'],
                'State Admin' => ['Department', 'Agency'],
            ];
            
            if ($selectedRole && isset($roleUnitMap[$selectedRole->name])) {
                $allowedTypes = $roleUnitMap[$selectedRole->name];
                
                if (!in_array($validated['administrative_type'], $allowedTypes)) {
                    return back()->withErrors([
                        'administrative_type' => "The role '{$selectedRole->name}' can only be assigned to: " . 
                            implode(' or ', $allowedTypes) . '.'
                    ])->withInput();
                }
            }
        }
        
        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'onboarded',
        ]);

        // Assign role
        $user->assignRole($selectedRole);

        // Assign administrative unit if provided
        if (!empty($validated['administrative_type']) && !empty($validated['administrative_id'])) {
            $user->administrative_type = "App\\Models\\{$validated['administrative_type']}";
            $user->administrative_id = $validated['administrative_id'];
            $user->save();
        }

        return redirect()
            ->route('super_admin.management.users.index')
            ->with('success', 'User created successfully and is now active.');
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
        // Define global roles that don't need administrative units
        $globalRoles = ['Super Admin', 'Governor'];
        
        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => ['required', Rule::in(['onboarded', 'pending', 'rejected'])],
        ];
        
        // Get the selected role to check if it needs administrative unit
        $selectedRole = null;
        if ($request->role_id) {
            $selectedRole = Role::find($request->role_id);
        }
        
        // Add administrative unit validation if role requires it
        if ($selectedRole && !in_array($selectedRole->name, $globalRoles)) {
            $rules['administrative_type'] = [
                'required', 
                'string', 
                Rule::in(['Department', 'Agency', 'LGA'])
            ];
            $rules['administrative_id'] = 'required|integer';
        }
        
        // Validate the request
        $validated = $request->validate($rules, [
            'administrative_type.required' => 'This role requires an administrative unit assignment.',
            'administrative_id.required' => 'Please select a specific administrative unit.',
        ]);
        
        // Additional validation: Check if administrative unit exists
        if (!empty($validated['administrative_type']) && !empty($validated['administrative_id'])) {
            $modelClass = "App\\Models\\{$validated['administrative_type']}";
            
            if (!class_exists($modelClass)) {
                return back()->withErrors(['administrative_type' => 'Invalid administrative type selected.'])->withInput();
            }
            
            $unitExists = $modelClass::where('id', $validated['administrative_id'])->exists();
            
            if (!$unitExists) {
                return back()->withErrors([
                    'administrative_id' => "The selected {$validated['administrative_type']} does not exist."
                ])->withInput();
            }
            
            // Validate role-unit compatibility
            $roleUnitMap = [
                'LGA Admin' => ['LGA'],
                'Enrollment Agent' => ['LGA'],
                'State Admin' => ['Department', 'Agency'],
            ];
            
            if ($selectedRole && isset($roleUnitMap[$selectedRole->name])) {
                $allowedTypes = $roleUnitMap[$selectedRole->name];
                
                if (!in_array($validated['administrative_type'], $allowedTypes)) {
                    return back()->withErrors([
                        'administrative_type' => "The role '{$selectedRole->name}' can only be assigned to: " . 
                            implode(' or ', $allowedTypes) . '.'
                    ])->withInput();
                }
            }
        }
        
        // Update user basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->status = $validated['status'];
        
        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Sync role
        $user->syncRoles([$selectedRole]);

        // Update administrative unit
        if (!empty($validated['administrative_type']) && !empty($validated['administrative_id'])) {
            $user->administrative_type = "App\\Models\\{$validated['administrative_type']}";
            $user->administrative_id = $validated['administrative_id'];
        } else {
            $user->administrative_type = null;
            $user->administrative_id = null;
        }
        
        $user->save();

        return redirect()
            ->route('super_admin.management.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
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