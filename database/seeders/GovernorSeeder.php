<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GovernorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
            'name' => 'Governor Office',
            'email' => 'governor@example.com',
            'password' => Hash::make('password'),
            'role' => 'governor',            
        ]);

        
    }
}