<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::withCount(['users', 'resources'])
            ->with('registeredBy:id,name')
            ->latest()
            ->get();
        
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $vendor = new Vendor();
        return view('admin.vendors.create', compact('vendor'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Company Information
            'legal_name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:50',
            'organization_type' => 'required|string',
            'establishment_date' => 'nullable|date',
            
            // Contact Information
            'contact_person_name' => 'required|string|max:255',
            'contact_person_title' => 'nullable|string|max:100',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'description' => 'required|string|max:500',
            
            // Business Details
            'focus_areas' => 'required|array',
            'focus_areas.*' => 'string|in:' . implode(',', array_keys((new Vendor)->getFocusAreaOptions())),
            'tax_identification_number' => 'nullable|string|max:50',
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            // Banking Information
            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:20',
            
            // Vendor Manager Account
            'manager_name' => 'required|string|max:255',
            'manager_email' => 'required|email|max:255|unique:users,email',
            'manager_phone' => 'required|string|max:20',
            'manager_password' => 'required|string|min:8|confirmed',
            
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Create Vendor
            $vendorData = $request->except([
                'registration_certificate', 
                'is_active',
                'manager_name',
                'manager_email', 
                'manager_phone',
                'manager_password',
                'manager_password_confirmation'
            ]);

            // Handle certificate upload
            if ($request->hasFile('registration_certificate')) {
                $file = $request->file('registration_certificate');
                $filename = 'certificate_' . time() . '_' . Str::slug($request->legal_name) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('vendor_certificates', $filename, 'public');
                $vendorData['registration_certificate'] = $path;
            }

            $vendorData['is_active'] = $request->has('is_active');
            $vendorData['registered_by'] = auth()->id();

            $vendor = Vendor::create($vendorData);

            // Create Vendor Manager User Account
            $vendorManagerRole = Role::where('name', 'Vendor Manager')->first();
            
            $user = User::create([
                'name' => $request->manager_name,
                'email' => $request->manager_email,
                'phone_number' => $request->manager_phone,
                'password' => Hash::make($request->manager_password),
                'vendor_id' => $vendor->id,
                'status' => 'onboarded',
                'email_verified_at' => now(),
            ]);

            $user->assignRole($vendorManagerRole);

            DB::commit();

            // Send notification email (optional)
            // Mail::to($user->email)->send(new VendorAccountCreated($user, $request->manager_password));

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor and Manager account created successfully. Login credentials have been set.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error creating vendor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Vendor $vendor)
    {
        $vendor->load(['users.roles', 'resources', 'registeredBy']);
        
        $stats = [
            'total_users' => $vendor->users()->count(),
            'vendor_managers' => $vendor->vendorManager()->count(),
            'distribution_agents' => $vendor->distributionAgents()->count(),
            'total_resources' => $vendor->resources()->count(),
            'proposed_resources' => $vendor->resources()->where('status', 'proposed')->count(),
            'active_resources' => $vendor->resources()->where('status', 'active')->count(),
        ];
        
        return view('admin.vendors.show', compact('vendor', 'stats'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'legal_name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:50',
            'organization_type' => 'required|string',
            'establishment_date' => 'nullable|date',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_title' => 'nullable|string|max:100',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'description' => 'required|string|max:500',
            'focus_areas' => 'required|array',
            'focus_areas.*' => 'string|in:' . implode(',', array_keys($vendor->getFocusAreaOptions())),
            'tax_identification_number' => 'nullable|string|max:50',
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vendorData = $request->except(['registration_certificate', 'is_active']);

            if ($request->hasFile('registration_certificate')) {
                if ($vendor->registration_certificate) {
                    Storage::disk('public')->delete($vendor->registration_certificate);
                }

                $file = $request->file('registration_certificate');
                $filename = 'certificate_' . time() . '_' . Str::slug($request->legal_name) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('vendor_certificates', $filename, 'public');
                $vendorData['registration_certificate'] = $path;
            }

            $vendorData['is_active'] = $request->has('is_active');

            $vendor->update($vendorData);

            return redirect()->route('admin.vendors.show', $vendor)
                ->with('success', 'Vendor information updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating vendor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Vendor $vendor)
    {
        try {
            // Check if vendor has associated resources
            if ($vendor->resources()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete vendor with associated resources. Please reassign or delete those resources first.');
            }

            // Check if vendor has users
            if ($vendor->users()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete vendor with associated user accounts. Please remove all users first.');
            }

            if ($vendor->registration_certificate) {
                Storage::disk('public')->delete($vendor->registration_certificate);
            }

            $vendor->delete();
            
            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor deleted successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting vendor: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Vendor $vendor)
    {
        try {
            $vendor->update(['is_active' => !$vendor->is_active]);
            
            $status = $vendor->is_active ? 'activated' : 'deactivated';
            
            return redirect()->back()
                ->with('success', "Vendor {$status} successfully");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating vendor status: ' . $e->getMessage());
        }
    }
}