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

        // Define All Permissions
        $permissions = [
            // Super Admin Permissions
            'manage_users', 'manage_roles', 'manage_lgas', 'manage_departments',
            'manage_agencies', 'system_settings', 'view_audit_logs', 'export_all_data',

            // Governor/State-level Permissions
            'view_governor_dashboard', 'view_state_analytics', 'manage_state_reports', 'manage_supplier_catalog',

            // Commissioner Permissions
            'view_commissioner_dashboard', 'view_commissioner_analytics', 'export_commissioner_reports',

            // LGA Admin Permissions
            'view_lga_dashboard', 'manage_lga_agents',

            // LGA-level Permissions
            'create_farmer_profile', 'edit_farmer_profile_own_lga', 'view_farmer_data_own_lga', 'manage_lga_manifests',

            // Enrollment Agent Permissions
            'enroll_farmers', 'verify_farmer_data', 'update_farmer_profiles',

            // Standard User Permissions
            'access_marketplace', 'apply_for_resource', 'view_own_submissions', 'manage_own_marketplace_listings',

            // Analytics Permissions
            'view_analytics', 'export_analytics',

            // Chat Support Permissions
            'view_support_chats', 'manage_support_chats', 'respond_to_support',

            // Cooperative Management Permissions
            'manage_lga_cooperatives', 'create_cooperatives', 'edit_cooperatives',
            'delete_cooperatives', 'manage_cooperative_members',
            'view_all_cooperatives', 'view_cooperative_details', 'export_cooperatives',
            'view_cooperative_overview', 'manage_all_cooperatives',

            // Vendor Management Permissions (NEW)
            'manage_vendors', 'create_vendors', 'edit_vendors', 'delete_vendors', 'view_all_vendors',
            
            // Vendor Manager Permissions (NEW)
            'view_vendor_dashboard', 'manage_vendor_team', 'propose_resources', 
            'view_vendor_analytics', 'manage_vendor_proposals', 'request_payouts',
            
            // Distribution Agent Permissions (NEW)
            'view_distribution_dashboard', 'search_farmers', 'mark_fulfilled', 'view_assigned_resources',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $governorRole = Role::firstOrCreate(['name' => 'Governor', 'guard_name' => 'web']);
        $commissionerRole = Role::firstOrCreate(['name' => 'Commissioner', 'guard_name' => 'web']);
        $stateAdminRole = Role::firstOrCreate(['name' => 'State Admin', 'guard_name' => 'web']);
        $lgaAdminRole = Role::firstOrCreate(['name' => 'LGA Admin', 'guard_name' => 'web']);
        $enrollmentAgentRole = Role::firstOrCreate(['name' => 'Enrollment Agent', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        
        // NEW VENDOR ROLES
        $vendorManagerRole = Role::firstOrCreate(['name' => 'Vendor Manager', 'guard_name' => 'web']);
        $distributionAgentRole = Role::firstOrCreate(['name' => 'Distribution Agent', 'guard_name' => 'web']);

        // Assign Permissions to Roles
        $superAdminRole->syncPermissions($permissions);

        $governorPermissions = [
            'view_governor_dashboard', 'view_state_analytics', 'manage_state_reports', 'export_all_data',
            'view_analytics', 'export_analytics', 'view_support_chats',
            'view_cooperative_overview', 'view_all_cooperatives', 'view_all_vendors',
        ];
        $governorRole->syncPermissions($governorPermissions);

        $commissionerPermissions = [
            'view_commissioner_dashboard', 'view_commissioner_analytics', 'export_commissioner_reports',
            'view_state_analytics', 'manage_state_reports', 'export_all_data',
            'view_analytics', 'export_analytics', 'view_support_chats',
            'view_cooperative_overview', 'view_all_cooperatives', 'view_all_vendors',
        ];
        $commissionerRole->syncPermissions($commissionerPermissions);

        $stateAdminPermissions = [
            'manage_users', 'manage_roles', 'manage_departments', 'manage_agencies',
            'manage_state_reports', 'manage_supplier_catalog', 'view_state_analytics',
            'view_analytics', 'export_analytics',
            'view_support_chats', 'manage_support_chats', 'respond_to_support',
            'view_all_cooperatives', 'view_cooperative_details', 'export_cooperatives',
            'manage_vendors', 'create_vendors', 'edit_vendors', 'delete_vendors', 'view_all_vendors',
        ];
        $stateAdminRole->syncPermissions($stateAdminPermissions);

        $lgaAdminPermissions = [
            'view_lga_dashboard', 'manage_lga_agents', 'create_farmer_profile',
            'edit_farmer_profile_own_lga', 'view_farmer_data_own_lga', 'manage_lga_manifests',
            'view_analytics', 'export_analytics',
            'view_support_chats', 'respond_to_support',
            'manage_lga_cooperatives', 'create_cooperatives', 'edit_cooperatives',
            'delete_cooperatives', 'manage_cooperative_members', 'view_cooperative_details',
            'export_cooperatives',
        ];
        $lgaAdminRole->syncPermissions($lgaAdminPermissions);

        $enrollmentAgentPermissions = [
            'enroll_farmers', 'verify_farmer_data', 'update_farmer_profiles', 'view_farmer_data_own_lga',
            'view_analytics', 'view_support_chats', 'respond_to_support',
        ];
        $enrollmentAgentRole->syncPermissions($enrollmentAgentPermissions);

        $userRole->syncPermissions([
            'access_marketplace', 'apply_for_resource', 'view_own_submissions', 'manage_own_marketplace_listings',
        ]);

        // NEW: Vendor Manager Permissions
        $vendorManagerPermissions = [
            'view_vendor_dashboard', 'manage_vendor_team', 'propose_resources',
            'view_vendor_analytics', 'manage_vendor_proposals', 'request_payouts',
        ];
        $vendorManagerRole->syncPermissions($vendorManagerPermissions);

        // NEW: Distribution Agent Permissions
        $distributionAgentPermissions = [
            'view_distribution_dashboard', 'search_farmers', 'mark_fulfilled', 'view_assigned_resources',
        ];
        $distributionAgentRole->syncPermissions($distributionAgentPermissions);

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

        User::firstOrCreate(['email' => 'commissioner@benue.gov.ng'], [
            'name' => 'Commissioner for Agriculture',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => 'onboarded',
            'administrative_id' => $dept_agric->id,
            'administrative_type' => Department::class,
        ])->syncRoles([$commissionerRole]);

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

        $this->command->info('Roles, permissions (including Vendor roles), and initial test users seeded successfully!');
    }
}