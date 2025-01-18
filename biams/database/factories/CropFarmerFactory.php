<?php

namespace Database\Factories;

use App\Models\Farmers\CropFarmer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farmers\CropFarmer>
 */
class CropFarmerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'phone' => $this->faker->phoneNumber(),
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'education' => $this->faker->randomElement(['Primary', 'Secondary', 'Tertiary']),
            'household_size' => $this->faker->numberBetween(1, 10),
            'dependents' => $this->faker->numberBetween(0, 5),
            'income_level' => $this->faker->randomElement(['Less than ₦100,000', 'Medium', 'High']),
            'lga' => $this->faker->randomElement(["Ado", 
                                "Agatu", 
                                "Apa", 
                                "Buruku", 
                                "Gboko", 
                                "Guma", 
                                "Gwer East", 
                                "Gwer West", 
                                "Katsina-Ala", 
                                "Konshisha", 
                                "Kwande", 
                                "Logo", 
                                "Makurdi", 
                                "Obi", 
                                "Ogbadibo", 
                                "Oju", 
                                "Ohimini", 
                                "Okpokwu", 
                                "Otpo", 
                                "Tarka", 
                                "Ukum", 
                                "Ushongo", 
                                "Vandeikya"]), 
            'farm_size' => $this->faker->randomFloat(2, 0.5, 10), 
            'farming_methods' => $this->faker->randomElement(['Traditional', 'Improved']),
            'seasonal_pattern' => $this->faker->randomElement(['Seasonal', 'Year-round']),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'farm_location' => $this->faker->address(),
            'crop' => $this->faker->randomElement(['Maize', 'Rice', 'Cassava', 'Soybean']),
            'other_crop' => $this->faker->word(), 
        ];
    }
}