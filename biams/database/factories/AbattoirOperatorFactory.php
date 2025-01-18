<?php

namespace Database\Factories;

use App\Models\Farmers\AbattoirOperator;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farmers\AbattoirOperator>
 */
class AbattoirOperatorFactory extends Factory
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
            'income_level' => $this->faker->randomElement(['Low', 'Medium', 'High']),
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
            'facility_type' => $this->faker->randomElement(['Mobile', 'Fixed']),
            'facility_specs' => $this->faker->text(),
            'operational_capacity' => $this->faker->numberBetween(10, 100),
            'certifications' => json_encode([]), // You can add sample certifications here if needed
        ];
    }
}