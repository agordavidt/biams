<?php


// database/seeders/UsersWithProfilesSeeder.php
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
        // Use Faker to generate fake data
        $faker = \Faker\Factory::create();

        // Create 10 users with profiles
        for ($i = 1; $i <= 10; $i++) {
            // Create a user
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Default password for all users
                'role' => 'user', // Assuming you have a 'role' column
                'status' => 'onboarded', // Assuming you have a 'status' column
                'email_verified_at' => Carbon::now()->subDays(rand(1, 365)), // Random verification date within the last year
            ]);

            // Create a profile for the user
            $user->profile()->create([
                'phone' => $faker->phoneNumber,
                'nin' => $faker->unique()->numerify('###########'), // Generate a unique 11-digit NIN
                'address' => $faker->address,
                'dob' => $faker->date(),
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'education' => $faker->randomElement(['High School', 'Bachelor', 'Master', 'PhD']),
                'household_size' => $faker->numberBetween(1, 10),
                'dependents' => $faker->numberBetween(0, 5),
                'income_level' => $faker->randomElement(['Low', 'Medium', 'High']),
                'lga' => $faker->randomElement(['Gwer West', 'Makurdi', 'Gboko']), // Add more LGAs as needed
            ]);
        }

        $this->command->info('10 users with profiles created successfully!');
    }
}