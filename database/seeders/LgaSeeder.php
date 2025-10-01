<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LGA;

class LgaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lgas = [
            // List of the 23 LGAs in Benue State (with illustrative codes)
           
            ['name' => 'Makurdi', 'code' => 'MDK'],
            ['name' => 'Gboko', 'code' => 'GBK'],
            ['name' => 'Gwer East', 'code' => 'GWE'],
            ['name' => 'Gwer West', 'code' => 'GWW'],
            ['name' => 'Vandeikya', 'code' => 'VND'],
            ['name' => 'Konshisha', 'code' => 'KNS'],
            ['name' => 'Buruku', 'code' => 'BRK'],
            ['name' => 'Ushongo', 'code' => 'USG'],
            ['name' => 'Logo', 'code' => 'LGO'],
            ['name' => 'Ukum', 'code' => 'UKM'],
            ['name' => 'Katsina-Ala', 'code' => 'KTA'],
            ['name' => 'Kwande', 'code' => 'KWD'],
            ['name' => 'Tarka', 'code' => 'TRK'],
            ['name' => 'Guma', 'code' => 'GUM'],            
            ['name' => 'Otukpo', 'code' => 'OTK'],
            ['name' => 'Okpokwu', 'code' => 'OPK'],
            ['name' => 'Ohimini', 'code' => 'OHM'],
            ['name' => 'Ogbadibo', 'code' => 'OGB'],
            ['name' => 'Agatu', 'code' => 'AGT'],
            ['name' => 'Apa', 'code' => 'APA'],
            ['name' => 'Oju', 'code' => 'OJU'],
            ['name' => 'Obi', 'code' => 'OBI'],
            ['name' => 'Ado', 'code' => 'ADO'],
        ];

        foreach ($lgas as $lga) {
            LGA::firstOrCreate(['name' => $lga['name']], $lga);
        }

        $this->command->info('Benue State LGAs seeded successfully!');
    }
}