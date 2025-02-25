<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Market\MarketplaceCategory;
use Illuminate\Support\Str;

class MarketplaceCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Grains',
                'description' => 'Various locally grown grains such as rice, maize, and sorghum.',
            ],
            [
                'name' => 'Tubers',
                'description' => 'Fresh tubers including cassava, yam, and other root crops.',
            ],
            [
                'name' => 'Fruits',
                'description' => 'Seasonal fruits from Nigerian farms, rich in flavor and nutrients.',
            ],
            [
                'name' => 'Livestock',
                'description' => 'Quality livestock including poultry, cattle, and goats for meat and dairy.',
            ],
        ];

        foreach ($categories as $data) {
            MarketplaceCategory::create([
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['description'],
                'is_active'   => true,
            ]);
        }
    }
}
