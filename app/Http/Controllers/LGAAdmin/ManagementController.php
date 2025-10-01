<?php

namespace App\Http\Controllers\LGAAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ManagementController extends Controller
{
    /**
     * Display a list of Enrollment Agents within the LGA.
     */
    public function index(): View
    {
        try {
            $lgaAdmin = auth()->user();
            
            // Validate that LGA Admin has proper administrative assignment
            if (!$lgaAdmin->administrative_id || !$lgaAdmin->administrative_type) {
                Log::warning('LGA Admin lacks proper administrative assignment', [
                    'user_id' => $lgaAdmin->id,
                    'email' => $lgaAdmin->email
                ]);
                
                return view('lga_admin.agents.index', [
                    'agents' => collect([]),
                    'error' => 'Your account is not properly configured. Please contact system administrator.'
                ]);
            }
            
            // Fetch enrollment agents for this specific LGA with eager loading
            $agents = User::role('Enrollment Agent')
                          ->where('administrative_id', $lgaAdmin->administrative_id)
                          ->where('administrative_type', $lgaAdmin->administrative_type)
                          ->with('administrativeUnit') // Eager load relationship
                          ->latest()
                          ->get();
            
            Log::info('LGA Admin viewed enrollment agents list', [
                'lga_admin_id' => $lgaAdmin->id,
                'agents_count' => $agents->count(),
                'lga_id' => $lgaAdmin->administrative_id
            ]);
                          
            return view('lga_admin.agents.index', compact('agents'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching enrollment agents', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return view('lga_admin.agents.index', [
                'agents' => collect([]),
                'error' => 'Unable to load agents. Please try again later.'
            ]);
        }
    }

    /**
     * Show the form for creating a new Enrollment Agent.
     */
    public function create(): View
    {
        $lgaAdmin = auth()->user();
        
        // Validate proper administrative assignment
        if (!$lgaAdmin->administrative_id || !$lgaAdmin->administrative_type) {
            return redirect()->route('lga_admin.agents.index')
                           ->with('error', 'Your account is not properly configured. Please contact system administrator.');
        }
        
        return view('lga_admin.agents.create');
    }

    /**
     * Store a newly created Enrollment Agent in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $lgaAdmin = auth()->user();
        
        // Pre-validation: Check administrative assignment
        if (!$lgaAdmin->administrative_id || !$lgaAdmin->administrative_type) {
            return redirect()->back()
                           ->with('error', 'Your account is not properly configured. Please contact system administrator.');
        }

        // Validation with custom messages
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s]+$/' // Only letters and spaces
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users,email',
                    'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
                ],
                'phone_number' => [
                    'nullable',
                    'string',
                    'max:20',
                    'unique:users,phone_number',
                    'regex:/^[0-9+\-\s()]+$/' // Allow phone number formats
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/' // At least 1 lowercase, 1 uppercase, 1 number
                ],
            ], [
                'name.regex' => 'Name should only contain letters and spaces.',
                'email.unique' => 'This email address is already registered.',
                'email.regex' => 'Please provide a valid email address.',
                'phone_number.unique' => 'This phone number is already registered.',
                'phone_number.regex' => 'Please provide a valid phone number.',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            ]);
            
        } catch (ValidationException $e) {
            Log::info('Validation failed for agent creation', [
                'lga_admin_id' => $lgaAdmin->id,
                'errors' => $e->errors()
            ]);
            throw $e;
        }
        
        DB::beginTransaction();
        
        try {
            // Create the agent
            $agent = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'phone_number' => $validated['phone_number'] ? trim($validated['phone_number']) : null,
                'password' => Hash::make($validated['password']),
                'status' => 'onboarded',
                'administrative_id' => $lgaAdmin->administrative_id,
                'administrative_type' => $lgaAdmin->administrative_type,
                'email_verified_at' => now(),
            ]);
            
            // Assign role
            $agent->assignRole('Enrollment Agent');
            
            DB::commit();
            
            Log::info('Enrollment Agent created successfully', [
                'agent_id' => $agent->id,
                'agent_email' => $agent->email,
                'created_by' => $lgaAdmin->id,
                'lga_id' => $lgaAdmin->administrative_id
            ]);

            return redirect()->route('lga_admin.agents.index')
                           ->with('success', 'Enrollment Agent account created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create Enrollment Agent', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lga_admin_id' => $lgaAdmin->id,
                'data' => $request->except('password', 'password_confirmation')
            ]);
            
            return redirect()->back()
                           ->withInput($request->except('password', 'password_confirmation'))
                           ->with('error', 'Failed to create agent account. Please try again or contact support if the problem persists.');
        }
    }

    /**
     * Show the form for editing an Enrollment Agent.
     */
    public function edit(User $agent): View|RedirectResponse
    {
        $lgaAdmin = auth()->user();
        
        // Authorization check: Ensure agent belongs to this LGA
        if ($agent->administrative_id !== $lgaAdmin->administrative_id || 
            $agent->administrative_type !== $lgaAdmin->administrative_type) {
            
            Log::warning('Unauthorized access attempt to edit agent', [
                'lga_admin_id' => $lgaAdmin->id,
                'agent_id' => $agent->id,
                'lga_admin_lga' => $lgaAdmin->administrative_id,
                'agent_lga' => $agent->administrative_id
            ]);
            
            abort(403, 'Unauthorized: This agent does not belong to your LGA.');
        }
        
        // Verify the user is actually an Enrollment Agent
        if (!$agent->hasRole('Enrollment Agent')) {
            Log::warning('Attempt to edit non-agent user', [
                'lga_admin_id' => $lgaAdmin->id,
                'user_id' => $agent->id,
                'user_roles' => $agent->roles->pluck('name')
            ]);
            
            abort(403, 'This user is not an Enrollment Agent.');
        }
        
        return view('lga_admin.agents.edit', compact('agent'));
    }

    /**
     * Update the specified Enrollment Agent.
     */
    public function update(Request $request, User $agent): RedirectResponse
    {
        $lgaAdmin = auth()->user();
        
        // Authorization check: Ensure agent belongs to this LGA
        if ($agent->administrative_id !== $lgaAdmin->administrative_id || 
            $agent->administrative_type !== $lgaAdmin->administrative_type) {
            
            Log::warning('Unauthorized update attempt', [
                'lga_admin_id' => $lgaAdmin->id,
                'agent_id' => $agent->id
            ]);
            
            abort(403, 'Unauthorized: This agent does not belong to your LGA.');
        }
        
        // Verify the user is actually an Enrollment Agent
        if (!$agent->hasRole('Enrollment Agent')) {
            abort(403, 'This user is not an Enrollment Agent.');
        }

        // Validation
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s]+$/'
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($agent->id),
                    'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
                ],
                'phone_number' => [
                    'nullable',
                    'string',
                    'max:20',
                    Rule::unique('users', 'phone_number')->ignore($agent->id),
                    'regex:/^[0-9+\-\s()]+$/'
                ],
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
                ],
                'status' => [
                    'required',
                    'in:pending,onboarded,rejected'
                ],
            ], [
                'name.regex' => 'Name should only contain letters and spaces.',
                'email.unique' => 'This email address is already registered.',
                'phone_number.unique' => 'This phone number is already registered.',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            ]);
            
        } catch (ValidationException $e) {
            Log::info('Validation failed for agent update', [
                'lga_admin_id' => $lgaAdmin->id,
                'agent_id' => $agent->id,
                'errors' => $e->errors()
            ]);
            throw $e;
        }
        
        DB::beginTransaction();
        
        try {
            // Prepare update data
            $updateData = [
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'phone_number' => $validated['phone_number'] ? trim($validated['phone_number']) : null,
                'status' => $validated['status'],
            ];
            
            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            
            // Update the agent
            $agent->update($updateData);
            
            DB::commit();
            
            Log::info('Enrollment Agent updated successfully', [
                'agent_id' => $agent->id,
                'updated_by' => $lgaAdmin->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return redirect()->route('lga_admin.agents.index')
                           ->with('success', 'Enrollment Agent updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update Enrollment Agent', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'agent_id' => $agent->id,
                'lga_admin_id' => $lgaAdmin->id
            ]);
            
            return redirect()->back()
                           ->withInput($request->except('password', 'password_confirmation'))
                           ->with('error', 'Failed to update agent account. Please try again or contact support if the problem persists.');
        }
    }

    /**
     * Remove the specified Enrollment Agent.
     */
    public function destroy(User $agent): RedirectResponse
    {
        $lgaAdmin = auth()->user();
        
        // Authorization check: Ensure agent belongs to this LGA
        if ($agent->administrative_id !== $lgaAdmin->administrative_id || 
            $agent->administrative_type !== $lgaAdmin->administrative_type) {
            
            Log::warning('Unauthorized delete attempt', [
                'lga_admin_id' => $lgaAdmin->id,
                'agent_id' => $agent->id
            ]);
            
            abort(403, 'Unauthorized: This agent does not belong to your LGA.');
        }
        
        // Verify the user is actually an Enrollment Agent
        if (!$agent->hasRole('Enrollment Agent')) {
            abort(403, 'This user is not an Enrollment Agent.');
        }
        
        DB::beginTransaction();
        
        try {
            $agentId = $agent->id;
            $agentEmail = $agent->email;
            
            // Soft delete or hard delete based on your requirements
            $agent->delete();
            
            DB::commit();
            
            Log::info('Enrollment Agent deleted successfully', [
                'agent_id' => $agentId,
                'agent_email' => $agentEmail,
                'deleted_by' => $lgaAdmin->id,
                'lga_id' => $lgaAdmin->administrative_id
            ]);

            return redirect()->route('lga_admin.agents.index')
                           ->with('success', 'Enrollment Agent deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete Enrollment Agent', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'agent_id' => $agent->id,
                'lga_admin_id' => $lgaAdmin->id
            ]);
            
            return redirect()->back()
                           ->with('error', 'Failed to delete agent account. The agent may have associated records that prevent deletion.');
        }
    }
}