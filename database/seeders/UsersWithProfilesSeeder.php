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
       
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
        
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), 
                'role' => 'user', 
                'status' => 'onboarded', 
                'email_verified_at' => Carbon::now()->subDays(rand(1, 365)), 
            ]);

          
            $user->profile()->create([
                'phone' => $faker->phoneNumber,
                'nin' => $faker->unique()->numerify('###########'), 
                'address' => $faker->address,
                'dob' => $faker->date(),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'education' => $faker->randomElement(['No formal education','Primary', 'Graduate', 'Post Graduate']),
                'household_size' => $faker->numberBetween(1, 10),
                'dependents' => $faker->numberBetween(0, 5),
                'income_level' => $faker->randomElement(['Low', 'Medium', 'High']),
                'lga' => $faker->randomElement(['Gwer West', 'Makurdi', 'Gboko', 'Otukpo', 'Oju', 'Konshisha']), 
            ]);
        }

        $this->command->info('10 users with profiles created successfully!');
    }
}