<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UsersWithProfilesSeeder extends Seeder
{
    public function run(): void
    {
        // Data for 10 users with Benue State names and context
        $usersData = [
            [
                'name' => 'Terkula Agbo',
                'email' => 'terkula.agbo@example.com',
                'gender' => 'Male',
                'lga' => 'Makurdi',
            ],
            [
                'name' => 'Wandoo Ikyo',
                'email' => 'wandoo.ikyo@example.com',
                'gender' => 'Female',
                'lga' => 'Gboko',
            ],
            [
                'name' => 'Ortese Ochai',
                'email' => 'ortese.ochai@example.com',
                'gender' => 'Male',
                'lga' => 'Otukpo',
            ],
            [
                'name' => 'Adoo Shom',
                'email' => 'adoo.shom@example.com',
                'gender' => 'Female',
                'lga' => 'Konshisha',
            ],
            [
                'name' => 'Iorlumun Tor',
                'email' => 'iorlumun.tor@example.com',
                'gender' => 'Male',
                'lga' => 'Gwer West',
            ],
            [
                'name' => 'Bem Aondo',
                'email' => 'bem.aondo@example.com',
                'gender' => 'Male',
                'lga' => 'Makurdi',
            ],
            [
                'name' => 'Nguvan Usha',
                'email' => 'nguvan.usha@example.com',
                'gender' => 'Female',
                'lga' => 'Gboko',
            ],
            [
                'name' => 'Agbo Ogbu',
                'email' => 'agbo.ogbu@example.com',
                'gender' => 'Male',
                'lga' => 'Otukpo',
            ],
            [
                'name' => 'Ene Ojile',
                'email' => 'ene.ojile@example.com',
                'gender' => 'Female',
                'lga' => 'Gwer West',
            ],
            [
                'name' => 'Aver Nyam',
                'email' => 'aver.nyam@example.com',
                'gender' => 'Female',
                'lga' => 'Makurdi',
            ],
        ];

        foreach ($usersData as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'onboarded',
                'email_verified_at' => Carbon::now(),
            ]);

            // Using Faker for generic data not specific to Benue
            $faker = \Faker\Factory::create();
            $user->profile()->create([
                'phone' => $faker->unique()->numerify('081########'),
                'nin' => $faker->unique()->numerify('###########'),
                'address' => $faker->address,
                'dob' => $faker->date(),
                'gender' => $userData['gender'],
                'education' => $faker->randomElement(['No formal education','Primary', 'Graduate', 'Post Graduate']),
                'household_size' => $faker->numberBetween(1, 10),
                'dependents' => $faker->numberBetween(0, 5),
                'income_level' => $faker->randomElement(['Low', 'Medium', 'High']),
                'lga' => $userData['lga'],
            ]);
        }

        $this->command->info('10 users with profiles created successfully!');
    }
}
