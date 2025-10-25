<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Get State Admin for registered_by
        $stateAdmin = User::where('email', 'stateadmin@benue.gov.ng')->first();
        
        if (!$stateAdmin) {
            $this->command->error('State Admin not found. Run RolesAndPermissionsSeeder first.');
            return;
        }

        // Get Vendor Manager Role
        $vendorManagerRole = Role::where('name', 'Vendor Manager')->first();
        $distributionAgentRole = Role::where('name', 'Distribution Agent')->first();

        // Create Test Vendor 1: Agro Supplies Ltd
        $vendor1 = Vendor::create([
            'legal_name' => 'Benue Agro Supplies Limited',
            'registration_number' => 'RC123456',
            'organization_type' => 'private_company',
            'establishment_date' => '2015-03-15',
            'contact_person_name' => 'John Okechukwu',
            'contact_person_title' => 'Managing Director',
            'contact_person_phone' => '08012345678',
            'contact_person_email' => 'john.okechukwu@agrobenuesupplies.com',
            'address' => '123 Market Road, Makurdi, Benue State',
            'website' => 'https://www.agrobenuesupplies.com',
            'description' => 'Leading supplier of quality agricultural inputs including certified seeds, fertilizers, and farm implements across Benue State.',
            'focus_areas' => ['agricultural_inputs', 'crop_production', 'agricultural_technology'],
            'tax_identification_number' => 'TIN-12345678',
            'bank_name' => 'First Bank of Nigeria',
            'bank_account_name' => 'Benue Agro Supplies Limited',
            'bank_account_number' => '1234567890',
            'is_active' => true,
            'registered_by' => $stateAdmin->id,
        ]);

        // Create Vendor Manager for Vendor 1
        $manager1 = User::create([
            'name' => 'James Ade',
            'email' => 'james.ade@agrobenuesupplies.com',
            'phone_number' => '08098765432',
            'password' => Hash::make('password'),
            'vendor_id' => $vendor1->id,
            'status' => 'onboarded',
            'email_verified_at' => now(),
        ]);
        $manager1->assignRole($vendorManagerRole);

        // Create Distribution Agent for Vendor 1
        $agent1 = User::create([
            'name' => 'Mary Atim',
            'email' => 'mary.atim@agrobenuesupplies.com',
            'phone_number' => '08087654321',
            'password' => Hash::make('password'),
            'vendor_id' => $vendor1->id,
            'status' => 'onboarded',
            'email_verified_at' => now(),
        ]);
        $agent1->assignRole($distributionAgentRole);

        // Create Test Vendor 2: Green Valley Farms
        $vendor2 = Vendor::create([
            'legal_name' => 'Green Valley Farms Cooperative',
            'registration_number' => 'COOP789012',
            'organization_type' => 'cooperative',
            'establishment_date' => '2018-07-20',
            'contact_person_name' => 'Sarah Nguvan',
            'contact_person_title' => 'Secretary',
            'contact_person_phone' => '08056789012',
            'contact_person_email' => 'sarah.nguvan@greenvalleyfarms.coop',
            'address' => '45 Farm Estate, Gboko, Benue State',
            'website' => null,
            'description' => 'Cooperative society providing agricultural extension services, training, and resource distribution to member farmers.',
            'focus_areas' => ['agricultural_extension', 'market_access', 'sustainable_agriculture'],
            'tax_identification_number' => null,
            'bank_name' => 'Zenith Bank',
            'bank_account_name' => 'Green Valley Farms Cooperative',
            'bank_account_number' => '9876543210',
            'is_active' => true,
            'registered_by' => $stateAdmin->id,
        ]);

        // Create Vendor Manager for Vendor 2
        $manager2 = User::create([
            'name' => 'Peter Ikyor',
            'email' => 'peter.ikyor@greenvalleyfarms.coop',
            'phone_number' => '08034567890',
            'password' => Hash::make('password'),
            'vendor_id' => $vendor2->id,
            'status' => 'onboarded',
            'email_verified_at' => now(),
        ]);
        $manager2->assignRole($vendorManagerRole);

        $this->command->info('Test vendors and vendor users seeded successfully!');
    }
}