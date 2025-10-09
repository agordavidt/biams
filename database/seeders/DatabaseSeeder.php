<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {        
       $this->call([
            LgaSeeder::class,
            DepartmentAndAgencySeeder::class,
            RolesAndPermissionsSeeder::class,
           MarketplaceCategorySeeder::class,
           
        ]);
      
    }
}
