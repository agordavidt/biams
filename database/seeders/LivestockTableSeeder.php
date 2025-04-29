<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LivestockTableSeeder extends Seeder
{
    public function run(): void
    {
        $species = ['cattle', 'goat', 'sheep', 'pig', 'other'];
        $genders = ['male', 'female'];
        $statuses = ['registered'];

        $northernStates = [
            'Kano', 'Kaduna', 'Katsina', 'Zamfara', 'Sokoto', 'Kebbi', 'Bauchi', 'Borno', 'Yobe', 'Jigawa', 'Niger', 'Plateau', 'Gombe'
        ];

        $lgas = [
            'Dala', 'Garko', 'Wudil', 'Zaria', 'Ikara', 'Daura', 'Funtua', 'Gusau', 'Talata Mafara', 'Sokoto North',
            'Birnin Kebbi', 'Misau', 'Maiduguri', 'Damaturu', 'Hadejia', 'Minna', 'Jos North', 'Gombe', 'Shani'
        ];

        $breeds = [
            'White Fulani', 'Sokoto Gudali', 'Red Bororo', 'West African Dwarf', 'Sahelian', 'Yankasa', 'Bunaji',
            'Nigerian Indigenous', 'Landrace', 'Large White', 'Balami', 'Others'
        ];

        $ownerFirstNames = ['Aliyu', 'Bello', 'Ibrahim', 'Yakubu', 'Hassan', 'Abubakar', 'Umar', 'Suleiman', 'Musa', 'Garba'];
        $ownerLastNames = ['Abdullahi', 'Mohammed', 'Usman', 'Tijani', 'Adamu', 'Danladi', 'Salihu', 'Kabiru', 'Abba', 'Isah'];

        $livestock = [];

        for ($i = 0; $i < 30; $i++) {
            $speciesVal = $species[array_rand($species)];
            $originState = $northernStates[array_rand($northernStates)];
            $originLGA = $lgas[array_rand($lgas)];
            $breed = rand(0, 1) ? $breeds[array_rand($breeds)] : null;

            $ownerName = $ownerFirstNames[array_rand($ownerFirstNames)] . ' ' . $ownerLastNames[array_rand($ownerLastNames)];

            $livestock[] = [
                'tracking_id' => 'LS-' . strtoupper(Str::random(8)),
                'species' => $speciesVal,
                'breed' => $breed,
                'origin_location' => $originLGA . ' Market',
                'origin_lga' => $originLGA,
                'origin_state' => $originState,
                'owner_name' => $ownerName,
                'owner_phone' => '080' . rand(10000000, 99999999),
                'owner_address' => $originLGA . ', ' . $originState,
                'registered_by' => 12, 
                'registration_date' => Carbon::now()->subDays(rand(1, 100))->format('Y-m-d'),
                'estimated_weight_kg' => rand(40, 500),
                'estimated_age_months' => rand(6, 60),
                'gender' => $genders[array_rand($genders)],
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('livestock')->insert($livestock);
    }
}
