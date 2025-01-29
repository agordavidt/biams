<?php


namespace Database\Seeders;

use App\Models\AgriculturalPractice;
use Illuminate\Database\Seeder;

class AgriculturalPracticesSeeder extends Seeder
{
    public function run()
    {
        $practices = [
            ['practice_name' => 'Crop Farming'],
            ['practice_name' => 'Animal Farming'],
            ['practice_name' => 'Processing and Value Addition'],
            ['practice_name' => 'Agricultural Services'],
            ['practice_name' => 'Aquaculture and Fisheries'],
            ['practice_name' => 'Agroforestry and Forestry'],
            ['practice_name' => 'Abattoir'],
        ];

        foreach ($practices as $practice) {
            AgriculturalPractice::create($practice);
        }
    }
}