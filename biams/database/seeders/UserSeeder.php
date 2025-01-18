<?php


namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Create 10 users with 'user' role
        User::factory()->count(100)->create([
            'role' => 'user',
        ]);

        // Create 2 users with 'admin' role
        User::factory()->count(2)->create([
            'role' => 'admin',
        ]);
    }
}
