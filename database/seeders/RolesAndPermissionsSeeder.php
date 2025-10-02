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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

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

        // Define Permissions
        $permissions = [
            // Super Admin Permissions
            'manage_users', 'manage_roles', 'manage_lgas', 'manage_departments',
            'manage_agencies', 'system_settings', 'view_audit_logs', 'export_all_data',

            // Governor/State-level Permissions
            'view_governor_dashboard', 'view_state_analytics', 'manage_state_reports', 'manage_supplier_catalog',

            // LGA Admin Permissions
            'view_lga_dashboard', 'manage_lga_agents', // Added manage_lga_agents

            // LGA-level Permissions
            'create_farmer_profile', 'edit_farmer_profile_own_lga', 'view_farmer_data_own_lga', 'manage_lga_manifests',

            // Enrollment Agent Permissions
            'enroll_farmers', 'verify_farmer_data', 'update_farmer_profiles',

            // Standard User Permissions
            'access_marketplace', 'apply_for_resource', 'view_own_submissions', 'manage_own_marketplace_listings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $governorRole   = Role::firstOrCreate(['name' => 'Governor', 'guard_name' => 'web']);
        $stateAdminRole = Role::firstOrCreate(['name' => 'State Admin', 'guard_name' => 'web']);
        $lgaAdminRole   = Role::firstOrCreate(['name' => 'LGA Admin', 'guard_name' => 'web']);
        $enrollmentAgentRole = Role::firstOrCreate(['name' => 'Enrollment Agent', 'guard_name' => 'web']); // Added
        $userRole       = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);

        // Assign Permissions to Roles
        $superAdminRole->syncPermissions($permissions);
        
        $governorRole->syncPermissions([
            'view_governor_dashboard', 'view_state_analytics', 'manage_state_reports', 'export_all_data',
        ]);
        
        $stateAdminRole->syncPermissions([
            'manage_users', 'manage_roles', 'manage_departments', 'manage_agencies',
            'manage_state_reports', 'manage_supplier_catalog', 'view_state_analytics',
        ]);
        
        $lgaAdminRole->syncPermissions([
            'view_lga_dashboard', 'manage_lga_agents', 'create_farmer_profile', 
            'edit_farmer_profile_own_lga', 'view_farmer_data_own_lga', 'manage_lga_manifests',
        ]);

        $enrollmentAgentRole->syncPermissions([
            'enroll_farmers', 'verify_farmer_data', 'update_farmer_profiles', 'view_farmer_data_own_lga',
        ]);
        
        $userRole->syncPermissions([
            'access_marketplace', 'apply_for_resource', 'view_own_submissions', 'manage_own_marketplace_listings',
        ]);

        // Create Initial Users
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

        User::firstOrCreate(['email' => 'farmer@test.com'], [
            'name' => 'Test Farmer User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'onboarded',
            'administrative_id' => $lga->id,
            'administrative_type' => LGA::class,
        ])->syncRoles([$userRole]);

        $this->command->info('✅ Roles, permissions, and initial test users seeded successfully!');
    }
}