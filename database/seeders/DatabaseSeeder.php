<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Order of seeding based on relationships:
     * 1. User roles (SuperAdmin, Admin, Governor) - no dependencies
     * 2. Regular users with profiles - no dependencies
     * 3. Marketplace categories - no dependencies
     * 4. Abattoirs - no dependencies
     * 5. Abattoir staff - depends on abattoirs
     * 6. Livestock - depends on users (registered_by field)   
     */
    public function run(): void
    {        
        // 1. Create system users (SuperAdmin, Admin, Governor)
        $this->call(SuperAdminSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(GovernorSeeder::class);
        
        // 2. Create regular users with profiles
        $this->call(UsersWithProfilesSeeder::class);
        
        // 3. Create marketplace categories
        $this->call(MarketplaceCategorySeeder::class);
        
        // 4. Create abattoirs
        $this->call(AbattoirsTableSeeder::class);
        
        // 5. Create abattoir staff (depends on abattoirs)
        $this->call(AbattoirStaffTableSeeder::class);
        
        // 6. Create livestock (depends on users for registered_by field)
        $this->call(LivestockTableSeeder::class);
        
      
    }
}
