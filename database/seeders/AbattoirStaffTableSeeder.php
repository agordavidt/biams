<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbattoirStaffTableSeeder extends Seeder
{
    public function run(): void
    {
        $benueNames = [
            'Terhemba', 'Dooshima', 'Iorfa', 'Ngutor', 'Oche', 'Terseer',
            'Mlumun', 'Seember', 'Iveren', 'Torkwase', 'Aondohemba', 'Ushahemba',
            'Kpamdoo', 'Tyoapine', 'Orseer', 'Ihotu', 'Bemshima', 'Tyokighir',
            'Aondona', 'Terkimbi'
        ];

        $roles = ['supervisor', 'meat_inspector', 'veterinary_officer', 'cleaner', 'security', 'other'];

        $abattoirs = DB::table('abattoirs')->get();

        $staff = [];

        $usedEmails = [];

    foreach ($abattoirs as $abattoir) {
        $generatedCount = 0;

        while ($generatedCount < 4) {
            $firstName = $benueNames[array_rand($benueNames)];
            $lastName = $benueNames[array_rand($benueNames)];
            $name = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName) . '@example.com';

            // Skip if this email was already used
            if (in_array($email, $usedEmails)) {
                continue;
            }

            $usedEmails[] = $email;

            $role = $roles[array_rand($roles)];
            $startDate = Carbon::now()->subDays(rand(30, 730))->format('Y-m-d');

            $staff[] = [
                'abattoir_id' => $abattoir->id,
                'name' => $name,
                'email' => $email,
                'phone' => '080' . rand(10000000, 99999999),
                'address' => 'Benue State',
                'role' => $role,
                'start_date' => $startDate,
                'end_date' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $generatedCount++;
        }
    }

        DB::table('abattoir_staff')->insert($staff);
    }
}
