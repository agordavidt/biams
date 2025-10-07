<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Market\MarketplaceCategory;
use Illuminate\Support\Str;

class MarketplaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Grains & Cereals',
                'description' => 'Rice, maize, sorghum, millet, wheat, and other cereal crops',
                'display_order' => 1,
            ],
            [
                'name' => 'Tubers & Roots',
                'description' => 'Yam, cassava, sweet potato, cocoyam, and other root crops',
                'display_order' => 2,
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Tomatoes, peppers, onions, leafy vegetables, and other fresh produce',
                'display_order' => 3,
            ],
            [
                'name' => 'Fruits',
                'description' => 'Oranges, mangoes, pineapples, bananas, and other tropical fruits',
                'display_order' => 4,
            ],
            [
                'name' => 'Legumes',
                'description' => 'Soybeans, groundnuts, beans, cowpeas, and other legumes',
                'display_order' => 5,
            ],
            [
                'name' => 'Livestock',
                'description' => 'Cattle, goats, sheep, pigs, and other farm animals',
                'display_order' => 6,
            ],
            [
                'name' => 'Poultry',
                'description' => 'Chickens, turkeys, ducks, guinea fowl, eggs, and poultry products',
                'display_order' => 7,
            ],
            [
                'name' => 'Fish & Aquaculture',
                'description' => 'Fresh fish, catfish, tilapia, and other aquaculture products',
                'display_order' => 8,
            ],
            [
                'name' => 'Cash Crops',
                'description' => 'Sesame, soybeans for export, palm products, and other cash crops',
                'display_order' => 9,
            ],
            [
                'name' => 'Processed Foods',
                'description' => 'Garri, flour, palm oil, groundnut oil, and other processed products',
                'display_order' => 10,
            ],
            [
                'name' => 'Seeds & Seedlings',
                'description' => 'Improved seeds, seedlings, planting materials',
                'display_order' => 11,
            ],
            [
                'name' => 'Farm Equipment',
                'description' => 'Tools, machinery, implements, and farming equipment',
                'display_order' => 12,
            ],
            [
                'name' => 'Fertilizers & Inputs',
                'description' => 'Fertilizers, pesticides, herbicides, and other farm inputs',
                'display_order' => 13,
            ],
            [
                'name' => 'Animal Feed',
                'description' => 'Livestock feed, poultry feed, and animal nutrition products',
                'display_order' => 14,
            ],
            [
                'name' => 'Other Agricultural Products',
                'description' => 'Honey, mushrooms, snails, and other farm products',
                'display_order' => 15,
            ],
        ];

        foreach ($categories as $category) {
            MarketplaceCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'display_order' => $category['display_order'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Marketplace categories seeded successfully!');
    }
}