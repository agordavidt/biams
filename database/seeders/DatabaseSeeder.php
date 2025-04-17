<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {        
        $this->call(UsersWithProfilesSeeder::class);
        $this->call(MarketplaceCategorySeeder::class);
        $this->call(SuperAdminSeeder::class);
        $this->call(AdminSeeder::class);
    }
}