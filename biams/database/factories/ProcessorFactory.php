<?php

namespace Database\Factories;
use App\Models\User;

use App\Models\Farmers\Processor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farmers\Processor>
 */
class ProcessorFactory extends Factory
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
            'processed_items' => json_encode(['Meat', 'Fish']), // Example processed items
            'processing_capacity' => $this->faker->randomFloat(1, 10, 100), 
            'equipment_type' => $this->faker->randomElement(['Manual', 'Semi-automated', 'Automated']),
            'equipment_specs' => $this->faker->text(),
        ];
    }
}