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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ManagementController extends Controller
{
    // Define Global and Unit-based roles for centralized logic
    protected $globalRoles = ['Super Admin', 'Governor'];
    protected $unitRoleMap = [
        'LGA Admin' => ['LGA'],
        'Enrollment Agent' => ['LGA'],
        'State Admin' => ['Department', 'Agency'], // Keeping the original business logic from the error description
    ];

    /**
     * Helper to get Role IDs that require an administrative unit.
     */
    protected function getUnitRoleIds()
    {
        $unitRoleNames = array_keys($this->unitRoleMap);
        return Role::whereIn('name', $unitRoleNames)->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }
    
    // --- General Management Index ---

    public function index()
    {
        return view('super_admin.management.index');
    }

    // --------------------------------------------------------------------------
    // User Management
    // --------------------------------------------------------------------------

    public function users()
    {
        // Policy Check: Assumes 'Super Admin' has permission via Spatie or Gates.
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::with(['roles', 'administrativeUnit'])->get();
        return view('super_admin.management.users.index', compact('users'));
    }

    public function createUser()
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::pluck('name', 'id');
        $unitRoles = Role::whereNotIn('name', $this->globalRoles)->pluck('name', 'id');
        $departments = Department::all();
        $agencies = Agency::all();
        $lgas = LGA::all();

        return view('super_admin.management.users.create', compact('roles', 'unitRoles', 'departments', 'agencies', 'lgas'));
    }

    public function storeUser(Request $request)
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }

        // 1. Define Base Rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
        ];

        $messages = [
            'administrative_type.required' => 'This role requires an administrative unit assignment.',
            'administrative_id.required' => 'Please select a specific administrative unit.',
            'role_id.required' => 'Please select a role for this user.',
        ];

        // 2. Conditional Unit Rules (If role requires unit)
        $role = Role::find($request->input('role_id'));
        if ($role && !in_array($role->name, $this->globalRoles)) {
            $rules['administrative_type'] = ['required', 'string', Rule::in(['Department', 'Agency', 'LGA'])];
            $rules['administrative_id'] = 'required|integer';
        }

        // 3. Run Validation
        $validator = Validator::make($request->all(), $rules, $messages);

        // 4. Custom Unit Existence and Compatibility Validation
        $validator->after(function ($validator) use ($role) {
            if ($this->shouldValidateUnitFields($role)) {
                // Check if administrative_id exists in the correct table (Department, Agency, or LGA)
                $this->validateAdministrativeUnitExistence($validator, $request);
                
                // Check Role-Unit Compatibility (The Fix)
                $this->validateRoleUnitCompatibility($validator, $request, $role);
            }
        });

        $data = $validator->validate();

        // 5. Data Manipulation for Storage
        $administrativeType = null;
        if (!empty($data['administrative_type'])) {
            $administrativeType = "App\\Models\\{$data['administrative_type']}";
        }
        
        // 6. Create User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'onboarded',
            'administrative_type' => $administrativeType,
            'administrative_id' => $data['administrative_id'] ?? null,
        ]);

        // 7. Assign Role
        $user->assignRole($role);

        return redirect()
            ->route('super_admin.management.users.index')
            ->with('success', 'User created successfully and is now active.');
    }

    public function editUser(User $user)
    {
        // Policy Check: Assumes 'Super Admin' can update any user.
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }
        // $this->authorize('update', $user); // Removed Policy

        $roles = Role::pluck('name', 'id');
        $unitRoles = Role::whereNotIn('name', $this->globalRoles)->pluck('name', 'id');
        $departments = Department::all();
        $agencies = Agency::all();
        $lgas = LGA::all();

        return view('super_admin.management.users.edit', compact('user', 'roles', 'unitRoles', 'departments', 'agencies', 'lgas'));
    }

    public function updateUser(Request $request, User $user)
    {
        // Policy Check: Assumes 'Super Admin' can update any user.
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }
        // $this->authorize('update', $user); // Removed Policy
        
        // 1. Define Base Rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', Rule::in(['onboarded', 'pending', 'rejected'])],
        ];
        
        $messages = [
            'administrative_type.required' => 'This role requires an administrative unit assignment.',
            'administrative_id.required' => 'Please select a specific administrative unit.',
            'role_id.required' => 'Please select a role for this user.',
        ];

        // 2. Conditional Unit Rules (If role requires unit)
        $role = Role::find($request->input('role_id'));
        if ($role && !in_array($role->name, $this->globalRoles)) {
            $rules['administrative_type'] = ['nullable', 'string', Rule::in(['Department', 'Agency', 'LGA'])];
            $rules['administrative_id'] = 'nullable|integer';
        } else {
            // For global roles, ensure administrative fields are explicitly nullable
            $rules['administrative_type'] = 'nullable';
            $rules['administrative_id'] = 'nullable';
        }

        // 3. Run Validation
        $validator = Validator::make($request->all(), $rules, $messages);

        // 4. Custom Unit Existence and Compatibility Validation
        $validator->after(function ($validator) use ($request, $role) {
            if ($this->shouldValidateUnitFields($role) && $request->filled('administrative_type') && $request->filled('administrative_id')) {
                // Check if administrative_id exists in the correct table (Department, Agency, or LGA)
                $this->validateAdministrativeUnitExistence($validator, $request);
                
                // Check Role-Unit Compatibility (The Fix)
                $this->validateRoleUnitCompatibility($validator, $request, $role);
            }
        });

        $data = $validator->validate();

        // 5. Data Manipulation
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->status = $data['status'];
        
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Handle Administrative Unit (Set to null if no unit is selected for a unit-based role, or if it's a global role)
        $administrativeType = null;
        $administrativeId = null;

        if ($request->filled('administrative_type') && $request->filled('administrative_id')) {
            $administrativeType = "App\\Models\\{$data['administrative_type']}";
            $administrativeId = $data['administrative_id'];
        }

        $user->administrative_type = $administrativeType;
        $user->administrative_id = $administrativeId;
        
        // 6. Sync Role
        $user->syncRoles([$role]);
        $user->save();

        return redirect()
            ->route('super_admin.management.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        // Policy Check: Assumes 'Super Admin' can delete any user except themselves.
        if (!Auth::user()->can('manage_users') || Auth::id() === $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('super_admin.management.users.index')->with('success', 'User deleted successfully.');
    }

    // --------------------------------------------------------------------------
    // Custom Validation Helpers
    // --------------------------------------------------------------------------

    /**
     * Determine if a role requires unit validation.
     */
    protected function shouldValidateUnitFields(Role $role = null)
    {
        return $role && !in_array($role->name, $this->globalRoles);
    }
    
    /**
     * Validate that the administrative_id exists in the correct table.
     */
    protected function validateAdministrativeUnitExistence($validator, $request): void
    {
        $type = $request->input('administrative_type');
        $id = $request->input('administrative_id');
        
        if (!$type || !$id) {
            return;
        }

        $modelClass = "App\\Models\\{$type}";
        
        if (!class_exists($modelClass)) {
            $validator->errors()->add('administrative_type', 'Invalid administrative type selected.');
            return;
        }
        
        $exists = $modelClass::where('id', $id)->exists();
        
        if (!$exists) {
            $validator->errors()->add('administrative_id', "The selected {$type} does not exist.");
        }
    }

    /**
     * Validate that the role is compatible with the selected administrative unit type.
     */
    protected function validateRoleUnitCompatibility($validator, $request, Role $role): void
    {
        $type = $request->input('administrative_type');

        if (!$type) {
            return;
        }

        // Check if the role is one that has specific unit restrictions
        if (isset($this->unitRoleMap[$role->name])) {
            $allowedTypes = $this->unitRoleMap[$role->name];
            
            if (!in_array($type, $allowedTypes)) {
                $validator->errors()->add(
                    'administrative_type',
                    "The role '{$role->name}' can only be assigned to: " . 
                    implode(' or ', $allowedTypes) . '.'
                );
            }
        }
    }


    // --------------------------------------------------------------------------
    // Department Management (Simplified without Request/Policy)
    // --------------------------------------------------------------------------

    public function departments()
    {
        if (!Auth::user()->can('manage_departments')) {
            abort(403, 'Unauthorized action.');
        }

        $departments = Department::withCount('users')->get();
        return view('super_admin.management.departments.index', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        if (!Auth::user()->can('manage_departments')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required|string|unique:departments,name|max:255',
            'abbreviation' => 'nullable|string|max:50',
        ]);

        Department::create($data);

        return response()->json(['success' => true, 'message' => 'Department created successfully.']);
    }

    public function updateDepartment(Request $request, Department $department)
    {
        if (!Auth::user()->can('manage_departments')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($department->id)],
            'abbreviation' => 'nullable|string|max:50',
        ]);

        $department->update($data);

        return response()->json(['success' => true, 'message' => 'Department updated successfully.']);
    }

    public function destroyDepartment(Department $department)
    {
        if (!Auth::user()->can('manage_departments')) {
            abort(403, 'Unauthorized action.');
        }

        if ($department->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete department with assigned users.'], 422);
        }

        $department->delete();
        return response()->json(['success' => true, 'message' => 'Department deleted successfully.']);
    }

    // --------------------------------------------------------------------------
    // Agency Management
    // --------------------------------------------------------------------------

    public function agencies()
    {
        if (!Auth::user()->can('manage_agencies')) {
            abort(403, 'Unauthorized action.');
        }

        $agencies = Agency::with('department')->withCount('users')->get();
        $departments = Department::all();
        return view('super_admin.management.agencies.index', compact('agencies', 'departments'));
    }

    public function storeAgency(Request $request)
    {
        if (!Auth::user()->can('manage_agencies')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required|string|unique:agencies,name|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Agency::create($data);

        return response()->json(['success' => true, 'message' => 'Agency created successfully.']);
    }

    public function updateAgency(Request $request, Agency $agency)
    {
        if (!Auth::user()->can('manage_agencies')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('agencies', 'name')->ignore($agency->id)],
            'department_id' => 'required|exists:departments,id',
        ]);

        $agency->update($data);

        return response()->json(['success' => true, 'message' => 'Agency updated successfully.']);
    }

    public function destroyAgency(Agency $agency)
    {
        if (!Auth::user()->can('manage_agencies')) {
            abort(403, 'Unauthorized action.');
        }

        if ($agency->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete agency with assigned users.'], 422);
        }

        $agency->delete();
        return response()->json(['success' => true, 'message' => 'Agency deleted successfully.']);
    }

    // --------------------------------------------------------------------------
    // LGA Management
    // --------------------------------------------------------------------------

    public function lgas()
    {
        if (!Auth::user()->can('manage_lgas')) {
            abort(403, 'Unauthorized action.');
        }

        $lgas = LGA::withCount('users')->get();
        return view('super_admin.management.lgas.index', compact('lgas'));
    }
}