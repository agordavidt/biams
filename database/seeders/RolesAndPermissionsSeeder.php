<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LGA;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Fetch required LGA and Department
        $lga = LGA::where('code', 'MDK')->first();
        if (!$lga) {
            $this->command->error("Makurdi LGA not found. Run LgaSeeder first.");
            return;
        }

        $dept_agric = Department::where('abbreviation', 'MAFS')->first();
        if (!$dept_agric) {
            $this->command->error("Ministry of Agriculture (MAFS) not found. Run DepartmentAndAgencySeeder first.");
            return;
        }

        // Standard Permissions
        $permissions = [
            // Super Admin Permissions
            'manage_users', 'manage_roles', 'manage_lgas', 'manage_departments',
            'manage_agencies', 'system_settings', 'view_audit_logs', 'export_all_data',

            // Governor/State-level Permissions
            'view_governor_dashboard', 'view_state_analytics', 'manage_state_reports', 'manage_supplier_catalog',

            // LGA Admin Permissions
            'view_lga_dashboard', 'manage_lga_agents',

            // LGA-level Permissions
            'create_farmer_profile', 'edit_farmer_profile_own_lga', 'view_farmer_data_own_lga', 'manage_lga_manifests',

            // Enrollment Agent Permissions
            'enroll_farmers', 'verify_farmer_data', 'update_farmer_profiles',

            // Standard User Permissions
            'access_marketplace', 'apply_for_resource', 'view_own_submissions', 'manage_own_marketplace_listings',
        ];

        // Analytics Permissions
        $analyticsPermissions = [
            'view_analytics',
            'export_analytics',
        ];

        // NEW: Chat Support Permissions
        $supportPermissions = [
            'view_support_chats',
            'manage_support_chats', // For Super/State Admin to manage system settings, status, assignment
            'respond_to_support',   // For active responders: State Admin, LGA Admin, Agent
        ];

        // Create all permissions first
        foreach (array_merge($permissions, $analyticsPermissions, $supportPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $governorRole   = Role::firstOrCreate(['name' => 'Governor', 'guard_name' => 'web']); // FIX APPLIED HERE
        $stateAdminRole = Role::firstOrCreate(['name' => 'State Admin', 'guard_name' => 'web']);
        $lgaAdminRole   = Role::firstOrCreate(['name' => 'Divisional Agriculture Officer', 'guard_name' => 'web']); // FIX APPLIED HERE
        $enrollmentAgentRole = Role::firstOrCreate(['name' => 'Enrollment Agent', 'guard_name' => 'web']);
        $userRole       = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']); // FIX APPLIED HERE

        // Assign permissions to roles
        // Super Admin gets EVERYTHING
        $superAdminRole->syncPermissions(array_merge($permissions, $analyticsPermissions, $supportPermissions));

        // Governor: Oversight (view only)
        $governorRole->syncPermissions([
            'view_governor_dashboard', 'view_state_analytics', 'manage_state_reports', 'export_all_data',
            'view_analytics', 'export_analytics',
            'view_support_chats', // Can view all chats for oversight
        ]);

        // State Admin: Full access and management of all chats
        $stateAdminRole->syncPermissions([
            'manage_users', 'manage_roles', 'manage_departments', 'manage_agencies',
            'manage_state_reports', 'manage_supplier_catalog', 'view_state_analytics',
            'view_analytics', 'export_analytics',
            'view_support_chats', 'manage_support_chats', 'respond_to_support',
        ]);

        // LGA Admin: View and respond to local chats
        $lgaAdminRole->syncPermissions([
            'view_lga_dashboard', 'manage_lga_agents', 'create_farmer_profile', 
            'edit_farmer_profile_own_lga', 'view_farmer_data_own_lga', 'manage_lga_manifests',
            'view_analytics', 'export_analytics',
            'view_support_chats', 'respond_to_support',
        ]);

        // Enrollment Agent: View and respond to local chats
        $enrollmentAgentRole->syncPermissions([
            'enroll_farmers', 'verify_farmer_data', 'update_farmer_profiles', 'view_farmer_data_own_lga',
            'view_analytics',
            'view_support_chats', 'respond_to_support',
        ]);

        // Standard User: No support permissions (chat access is handled by policy based on farmer_id)
        $userRole->syncPermissions([
            'access_marketplace', 'apply_for_resource', 'view_own_submissions', 'manage_own_marketplace_listings',
        ]);

        // Create initial users
        User::firstOrCreate(['email' => 'superadmin@benue.gov.ng'], [
            'name' => 'System Super Administrator',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'onboarded',
        ])->syncRoles([$superAdminRole]);

        User::firstOrCreate(['email' => 'governor@benue.gov.ng'], [
            'name' => 'Executive Governor',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'onboarded',
        ])->syncRoles([$governorRole]);

        User::firstOrCreate(['email' => 'stateadmin@benue.gov.ng'], [
            'name' => 'State Administrator (Agric)',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'onboarded',
            'administrative_id' => $dept_agric->id,
            'administrative_type' => Department::class,
        ])->syncRoles([$stateAdminRole]);

        User::firstOrCreate(['email' => 'lgaadmin@makurdi.gov.ng'], [
            'name' => 'Makurdi LGA Administrator',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'onboarded',
            'administrative_id' => $lga->id,
            'administrative_type' => LGA::class,
        ])->syncRoles([$lgaAdminRole]);

        User::firstOrCreate(['email' => 'agent@makurdi.gov.ng'], [
            'name' => 'Test Enrollment Agent',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'onboarded',
            'administrative_id' => $lga->id,
            'administrative_type' => LGA::class,
        ])->syncRoles([$enrollmentAgentRole]);

        // User::firstOrCreate(['email' => 'farmer@test.com'], [
        //     'name' => 'Test Farmer User',
        //     'password' => Hash::make('password'),
        //     'email_verified_at' => now(),
        //     'status' => 'onboarded',
        //     'administrative_id' => $lga->id,
        //     'administrative_type' => LGA::class,
        // ])->syncRoles([$userRole]);

        $this->command->info('Roles, permissions, and initial test users seeded successfully!');
    }
}
