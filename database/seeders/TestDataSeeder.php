<?php

namespace Database\Seeders;

use App\Models\Abattoir;
use App\Models\AbattoirStaff;
use App\Models\Livestock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 Users who register livestock
        $users = [
            ['name' => 'Suleiman Alhaji', 'email' => 'admin1@example.com'],
            ['name' => 'Eneh Idoko', 'email' => 'admin2@example.com'],
            ['name' => 'Hembadoon Saor', 'email' => 'officerben@example.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
            ]);
        }

        // Abattoirs
        $abattoirs = [
            ['name' => 'Makurdi Abattoir', 'registration_number' => 'AB001', 'license_number' => 'LN001', 'lga' => 'Makurdi', 'address' => '123 Street', 'capacity' => 100, 'status' => 'active'],
            ['name' => 'Zaki Abattoir', 'registration_number' => 'AB002', 'license_number' => 'LN002', 'lga' => 'Gboko', 'address' => 'Road 45 Gboko', 'capacity' => 120, 'status' => 'active'],
            ['name' => 'Greenfield Slaughterhouse', 'registration_number' => 'AB003', 'license_number' => 'LN003', 'lga' => 'Enugu North', 'address' => 'Main Street, Enugu', 'capacity' => 80, 'status' => 'inactive'],
            ['name' => 'Ajegunle Municipal', 'registration_number' => 'AB004', 'license_number' => 'LN004', 'lga' => 'Ajeromi-Ifelodun', 'address' => 'Boundary Street', 'capacity' => 200, 'status' => 'active'],
            ['name' => 'Yola Central Abattoir', 'registration_number' => 'AB005', 'license_number' => 'LN005', 'lga' => 'Yola South', 'address' => 'Yola Market Road', 'capacity' => 150, 'status' => 'active'],
            ['name' => 'Port Harcourt Meat Hub', 'registration_number' => 'AB006', 'license_number' => 'LN006', 'lga' => 'Obio-Akpor', 'address' => 'PHC Main Abattoir', 'capacity' => 180, 'status' => 'active'],
        ];

        foreach ($abattoirs as $data) {
            Abattoir::create($data);
        }

        // Abattoir Staff
        $staffs = [
            ['abattoir_id' => 1, 'name' => 'John Vet', 'email' => 'john@example.com', 'phone' => '1234567890', 'role' => 'veterinary_officer'],
            ['abattoir_id' => 1, 'name' => 'Jane Inspector', 'email' => 'jane@example.com', 'phone' => '0987654321', 'role' => 'meat_inspector'],
            ['abattoir_id' => 2, 'name' => 'Chukwu Daniel', 'email' => 'daniel@example.com', 'phone' => '08011112222', 'role' => 'veterinary_officer'],
            ['abattoir_id' => 3, 'name' => 'Halima Binta', 'email' => 'halima@example.com', 'phone' => '08123456789', 'role' => 'meat_inspector'],
            ['abattoir_id' => 4, 'name' => 'Ali Musa', 'email' => 'ali@example.com', 'phone' => '08032223344', 'role' => 'veterinary_officer'],
            ['abattoir_id' => 5, 'name' => 'Grace Bello', 'email' => 'grace@example.com', 'phone' => '07087776655', 'role' => 'meat_inspector'],
        ];

        foreach ($staffs as $staff) {
            AbattoirStaff::create(array_merge($staff, [
                'start_date' => now(),
                'is_active' => true,
            ]));
        }

        // Livestock
        $livestock = [
            ['tracking_id' => 'LS-001', 'species' => 'cattle', 'origin_location' => 'Makurdi', 'origin_lga' => 'Makurdi', 'origin_state' => 'Benue', 'owner_name' => 'Farmer Joe', 'registered_by' => 1, 'gender' => 'male'],
            ['tracking_id' => 'LS-002', 'species' => 'goat', 'origin_location' => 'Gboko', 'origin_lga' => 'Gboko', 'origin_state' => 'Benue', 'owner_name' => 'Ngozi Farm', 'registered_by' => 2, 'gender' => 'female'],
            ['tracking_id' => 'LS-003', 'species' => 'pig', 'origin_location' => 'Nsukka', 'origin_lga' => 'Nsukka', 'origin_state' => 'Enugu', 'owner_name' => 'Uche Agro', 'registered_by' => 1, 'gender' => 'male'],
            ['tracking_id' => 'LS-004', 'species' => 'sheep', 'origin_location' => 'Zaria', 'origin_lga' => 'Zaria', 'origin_state' => 'Kaduna', 'owner_name' => 'Alhaji Bello', 'registered_by' => 3, 'gender' => 'female'],
            ['tracking_id' => 'LS-005', 'species' => 'cattle', 'origin_location' => 'Yola', 'origin_lga' => 'Yola South', 'origin_state' => 'Adamawa', 'owner_name' => 'Yola Farms', 'registered_by' => 1, 'gender' => 'male'],
            ['tracking_id' => 'LS-006', 'species' => 'goat', 'origin_location' => 'Ikeja', 'origin_lga' => 'Ikeja', 'origin_state' => 'Lagos', 'owner_name' => 'Lagos Livestock Ltd.', 'registered_by' => 2, 'gender' => 'female'],
        ];

        foreach ($livestock as $animal) {
            Livestock::create(array_merge($animal, [
                'registration_date' => now(),
                'estimated_weight_kg' => rand(40, 350),
                'estimated_age_months' => rand(6, 36),
                'status' => 'registered',
            ]));
        }
    }
}
