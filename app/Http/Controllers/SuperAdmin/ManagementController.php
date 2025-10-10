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
use Illuminate\Support\Facades\Log;

class ManagementController extends Controller
{
    protected $globalRoles = ['Super Admin', 'Governor'];
    protected $unitRoleMap = [
        'Divisional Agriculture Officer' => ['LGA'], // Changed from 'LGA Admin'
        'Enrollment Agent' => ['LGA'],
        'State Admin' => ['Department', 'Agency'],
    ];

    protected function getUnitRoleIds()
    {
        $unitRoleNames = array_keys($this->unitRoleMap);
        return Role::whereIn('name', $unitRoleNames)->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }
    
    public function index()
    {
        return view('super_admin.management.index');
    }

    public function users()
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load roles and administrative units
        $users = User::with(['roles'])->get()->map(function($user) {
            // Manually load the administrative unit if exists
            if ($user->administrative_type && $user->administrative_id) {
                $unitClass = $user->administrative_type;
                if (class_exists($unitClass)) {
                    $user->loadedAdministrativeUnit = $unitClass::find($user->administrative_id);
                }
            }
            return $user;
        });
        
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

        // Log incoming request for debugging
        Log::info('Store User Request', [
            'role_id' => $request->input('role_id'),
            'administrative_type' => $request->input('administrative_type'),
            'administrative_id' => $request->input('administrative_id'),
        ]);

        // 1. Define Base Rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
        ];

        $messages = [
            'administrative_type.required' => 'This role requires an administrative unit type to be selected.',
            'administrative_id.required' => 'Please select a specific administrative unit.',
            'role_id.required' => 'Please select a role for this user.',
        ];

        // 2. Get the role to check if it requires administrative unit
        $role = Role::find($request->input('role_id'));
        
        // 3. Add conditional rules if role requires unit
        if ($role && !in_array($role->name, $this->globalRoles)) {
            $rules['administrative_type'] = ['required', 'string', Rule::in(['Department', 'Agency', 'LGA'])];
            $rules['administrative_id'] = ['required', 'integer', 'min:1'];
        }

        // 4. Run Validation
        $validator = Validator::make($request->all(), $rules, $messages);

        // 5. Custom validation in after callback
        $validator->after(function ($validator) use ($request, $role) { 
            if ($this->shouldValidateUnitFields($role)) {
                $this->validateAdministrativeUnitExistence($validator, $request); 
                $this->validateRoleUnitCompatibility($validator, $request, $role); 
            }
        });

        // 6. Validate and get data
        $data = $validator->validate();

        // 7. Prepare administrative unit data
        $administrativeType = null;
        $administrativeId = null;
        
        // KEY FIX: Check if role requires unit and data is provided
        if ($role && !in_array($role->name, $this->globalRoles)) {
            // For unit-based roles, these fields are required by validation
            $shortType = $data['administrative_type']; // e.g., "Department", "Agency", "LGA"
            $administrativeType = "App\\Models\\{$shortType}";
            $administrativeId = $data['administrative_id'];
            
            // Additional safety check
            if (!$administrativeId || $administrativeId < 1) {
                return back()->withErrors([
                    'administrative_id' => 'A valid administrative unit must be selected for this role.'
                ])->withInput();
            }
        }
        
        // 8. Create User with explicit field assignment
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'onboarded',
            'administrative_type' => $administrativeType,
            'administrative_id' => $administrativeId,
        ]);

        // Log what was saved
        Log::info('User Created', [
            'user_id' => $user->id,
            'administrative_type' => $user->administrative_type,
            'administrative_id' => $user->administrative_id,
        ]);

        // 9. Assign Role
        $user->assignRole($role);

        return redirect()
            ->route('super_admin.management.users.index')
            ->with('success', 'User created successfully and is now active.');
    }

    public function editUser(User $user)
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::pluck('name', 'id');
        $unitRoles = Role::whereNotIn('name', $this->globalRoles)->pluck('name', 'id');
        $departments = Department::all();
        $agencies = Agency::all();
        $lgas = LGA::all();

        return view('super_admin.management.users.edit', compact('user', 'roles', 'unitRoles', 'departments', 'agencies', 'lgas'));
    }

    public function updateUser(Request $request, User $user)
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Unauthorized action.');
        }

        // Log incoming request
        Log::info('Update User Request', [
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
            'administrative_type' => $request->input('administrative_type'),
            'administrative_id' => $request->input('administrative_id'),
        ]);
        
        // 1. Define Base Rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', Rule::in(['onboarded', 'pending', 'rejected'])],
        ];
        
        $messages = [
            'administrative_type.required' => 'This role requires an administrative unit type to be selected.',
            'administrative_id.required' => 'Please select a specific administrative unit.',
            'role_id.required' => 'Please select a role for this user.',
        ];

        // 2. Get the role
        $role = Role::find($request->input('role_id'));
        
        // 3. Conditional Unit Rules
        if ($role && !in_array($role->name, $this->globalRoles)) {
            $rules['administrative_type'] = ['required', 'string', Rule::in(['Department', 'Agency', 'LGA'])];
            $rules['administrative_id'] = ['required', 'integer', 'min:1'];
        } else {
            // For global roles, make fields nullable
            $rules['administrative_type'] = 'nullable';
            $rules['administrative_id'] = 'nullable';
        }

        // 4. Run Validation
        $validator = Validator::make($request->all(), $rules, $messages);

        // 5. Custom validation
        $validator->after(function ($validator) use ($request, $role) {
            if ($this->shouldValidateUnitFields($role) && $request->filled('administrative_type') && $request->filled('administrative_id')) {
                $this->validateAdministrativeUnitExistence($validator, $request);
                $this->validateRoleUnitCompatibility($validator, $request, $role);
            }
        });

        $data = $validator->validate();

        // 6. Update basic fields
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->status = $data['status'];
        
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // 7. Handle Administrative Unit
        if ($role && !in_array($role->name, $this->globalRoles)) {
            // Unit-based role - set administrative fields
            $shortType = $data['administrative_type'];
            $user->administrative_type = "App\\Models\\{$shortType}";
            $user->administrative_id = $data['administrative_id'];
        } else {
            // Global role - clear administrative fields
            $user->administrative_type = null;
            $user->administrative_id = null;
        }

        // Log what will be saved
        Log::info('User Update', [
            'user_id' => $user->id,
            'administrative_type' => $user->administrative_type,
            'administrative_id' => $user->administrative_id,
        ]);
        
        // 8. Sync Role and Save
        $user->syncRoles([$role]);
        $user->save();

        return redirect()
            ->route('super_admin.management.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if (!Auth::user()->can('manage_users') || Auth::id() === $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('super_admin.management.users.index')->with('success', 'User deleted successfully.');
    }

    protected function shouldValidateUnitFields(Role $role = null)
    {
        return $role && !in_array($role->name, $this->globalRoles);
    }
    
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

    protected function validateRoleUnitCompatibility($validator, $request, Role $role): void
    {
        $type = $request->input('administrative_type');

        if (!$type) {
            return;
        }

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

    // Department, Agency, LGA management methods remain the same...
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

    public function lgas()
    {
        if (!Auth::user()->can('manage_lgas')) {
            abort(403, 'Unauthorized action.');
        }

        $lgas = LGA::withCount('users')->get();
        return view('super_admin.management.lgas.index', compact('lgas'));
    }
}