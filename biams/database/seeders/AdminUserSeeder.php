<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    User::create([
        'name' => 'David Admin',
        'email' => 'admin@benueagri.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
}
}
