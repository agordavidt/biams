<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbattoirsTableSeeder extends Seeder
{
    public function run(): void
    {
        $abattoirs = [
            [
                'name' => 'Makurdi Central Abattoir',
                'registration_number' => 'BN/ABT/001',
                'license_number' => 'LIC-BN-001',
                'address' => 'Wurukum Market Road, Makurdi',
                'lga' => 'Makurdi',
                'gps_latitude' => '7.7336',
                'gps_longitude' => '8.5361',
                'capacity' => 500,
                'status' => 'active',
                'description' => 'Central facility serving Makurdi metropolis.',
            ],
            [
                'name' => 'Gboko Livestock Centre',
                'registration_number' => 'BN/ABT/002',
                'license_number' => 'LIC-BN-002',
                'address' => 'Near Yandev Roundabout, Gboko',
                'lga' => 'Gboko',
                'gps_latitude' => '7.3255',
                'gps_longitude' => '9.0028',
                'capacity' => 300,
                'status' => 'active',
                'description' => 'Main livestock processing center in Gboko.',
            ],
            [
                'name' => 'Otukpo Meat Depot',
                'registration_number' => 'BN/ABT/003',
                'license_number' => 'LIC-BN-003',
                'address' => 'Main Market Road, Otukpo',
                'lga' => 'Otukpo',
                'gps_latitude' => '7.1907',
                'gps_longitude' => '8.1319',
                'capacity' => 250,
                'status' => 'inactive',
                'description' => 'Under renovation by the local government.',
            ],
            [
                'name' => 'Katsina-Ala Cattle Market Abattoir',
                'registration_number' => 'BN/ABT/004',
                'license_number' => 'LIC-BN-004',
                'address' => 'Along Takum Road, Katsina-Ala',
                'lga' => 'Katsina-Ala',
                'gps_latitude' => '7.1659',
                'gps_longitude' => '9.2860',
                'capacity' => 400,
                'status' => 'active',
                'description' => 'Feeds the northern meat supply network.',
            ],
            [
                'name' => 'Vandeikya Butchery Point',
                'registration_number' => 'BN/ABT/005',
                'license_number' => 'LIC-BN-005',
                'address' => 'High Level, Vandeikya Town',
                'lga' => 'Vandeikya',
                'gps_latitude' => '7.1133',
                'gps_longitude' => '9.0234',
                'capacity' => 200,
                'status' => 'active',
                'description' => 'Serves rural meat supply and transport.',
            ],
            [
                'name' => 'Zaki Biam Yam Market Abattoir',
                'registration_number' => 'BN/ABT/006',
                'license_number' => 'LIC-BN-006',
                'address' => 'Market Road, Zaki-Biam',
                'lga' => 'Ukum',
                'gps_latitude' => '7.4981',
                'gps_longitude' => '9.2420',
                'capacity' => 350,
                'status' => 'active',
                'description' => 'Close to Nigeria’s largest yam market.',
            ],
            [
                'name' => 'Aliade Slaughterhouse',
                'registration_number' => 'BN/ABT/007',
                'license_number' => 'LIC-BN-007',
                'address' => 'Behind Central Market, Aliade',
                'lga' => 'Gwer East',
                'gps_latitude' => '7.2950',
                'gps_longitude' => '8.6799',
                'capacity' => 220,
                'status' => 'suspended',
                'description' => 'Currently undergoing inspection by state vets.',
            ],
            [
                'name' => 'Buruku Livestock Centre',
                'registration_number' => 'BN/ABT/008',
                'license_number' => 'LIC-BN-008',
                'address' => 'Tse-Agberagba Road, Buruku',
                'lga' => 'Buruku',
                'gps_latitude' => '7.3134',
                'gps_longitude' => '9.0023',
                'capacity' => 180,
                'status' => 'active',
                'description' => 'Feeds local communities and smaller towns.',
            ],
            [
                'name' => 'Adikpo Abattoir',
                'registration_number' => 'BN/ABT/009',
                'license_number' => 'LIC-BN-009',
                'address' => 'Adikpo Market Area, Kwande',
                'lga' => 'Kwande',
                'gps_latitude' => '7.2829',
                'gps_longitude' => '9.0556',
                'capacity' => 300,
                'status' => 'active',
                'description' => 'Hub for meat trade to neighboring states.',
            ],
            [
                'name' => 'Oju Municipal Abattoir',
                'registration_number' => 'BN/ABT/010',
                'license_number' => 'LIC-BN-010',
                'address' => 'Behind LG Secretariat, Oju',
                'lga' => 'Oju',
                'gps_latitude' => '6.8467',
                'gps_longitude' => '8.4089',
                'capacity' => 150,
                'status' => 'inactive',
                'description' => 'Awaiting new health certification.',
            ],
        ];

        foreach ($abattoirs as &$abattoir) {
            $abattoir['created_at'] = now();
            $abattoir['updated_at'] = now();
        }

        DB::table('abattoirs')->insert($abattoirs);
    }
}
