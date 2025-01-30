<?php


namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
           
        ]);

        // Create a regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'pending',
            'phone' => '0987654321',
            'dob' => '1990-01-01',
            'gender' => 'Female',
            'education' => 'Secondary',
            'household_size' => 5,
            'dependents' => 3,
            'income_level' => 'N200000 - N250000',
            'lga' => 'Makurid',
        ]);
    }
}


