<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Agency;

class DepartmentAndAgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Departments
        $dept_agric = Department::firstOrCreate([
            'name' => 'Ministry of Agriculture and Food Security',
            'abbreviation' => 'MAFS',
        ]);
        
        $dept_dev = Department::firstOrCreate([
            'name' => 'Agricultural Development and Empowerment Programmes',
            'abbreviation' => 'ADEP',
        ]);

        $dept_water = Department::firstOrCreate([
            'name' => 'Ministry of Water Resources and Environment',
            'abbreviation' => 'MWRE',
        ]);
        
        $dept_land = Department::firstOrCreate([
            'name' => 'Ministry of Lands and Survey',
            'abbreviation' => 'MLS',
        ]);


        // 2. Seed Agencies linked to Departments
        
        // Agencies under MAFS
        Agency::firstOrCreate([
            'name' => 'Mechanised Farming Pilot Scheme',
            'department_id' => $dept_agric->id
        ]);

        Agency::firstOrCreate([
            'name' => 'Benue State Agricultural and Rural Development Authority (BNARDA)',
            'department_id' => $dept_agric->id
        ]);
        
        // Agencies under ADEP
        Agency::firstOrCreate([
            'name' => 'Benue State Agricultural Development Corporation (BSADC)',
            'department_id' => $dept_dev->id
        ]);

        Agency::firstOrCreate([
            'name' => 'Agricultural Resource Management Agency',
            'department_id' => $dept_dev->id
        ]);
        
        // Agencies under MWRE (Example)
        Agency::firstOrCreate([
            'name' => 'Water and Soil Conservation Unit',
            'department_id' => $dept_water->id
        ]);


        $this->command->info('✅ Departments and Agencies seeded successfully!');
    }
}