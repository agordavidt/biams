<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('resources')->insert([
            [
                'name' => 'Introduction to Maize Farming in Benue',
                'description' => 'A beginner-friendly guide covering the basics of maize cultivation in the Benue climate.',
                'price' => 0.00,
                'requires_payment' => false,
                'credo_merchant_id' => null,
                'form_fields' => json_encode([
                    ['label' => 'Full Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Phone Number', 'type' => 'tel', 'required' => true],
                    ['label' => 'Experience Level', 'type' => 'select', 'options' => 'Beginner, Intermediate, Advanced', 'required' => true],
                ]),
                'target_practice' => 'crop-farmer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Poultry Vaccination Schedule',
                'description' => 'A recommended vaccination schedule for common poultry diseases in Benue State.',
                'price' => 500.00,
                'requires_payment' => true,
                'credo_merchant_id' => 'YOUR_CREDO_MERCHANT_ID_1',
                'form_fields' => json_encode([
                    ['label' => 'Farm Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Contact Person', 'type' => 'text', 'required' => true],
                    ['label' => 'Number of Birds', 'type' => 'number', 'required' => true],
                    ['label' => 'Proof of Payment', 'type' => 'file', 'required' => true],
                ]),
                'target_practice' => 'animal-farmer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hygiene Best Practices in Abattoirs',
                'description' => 'A guide outlining the essential hygiene standards for abattoir operations to ensure food safety.',
                'price' => 0.00,
                'requires_payment' => false,
                'credo_merchant_id' => null,
                'form_fields' => json_encode([
                    ['label' => 'Abattoir Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Contact Person', 'type' => 'text', 'required' => true],
                    ['label' => 'Position', 'type' => 'text', 'required' => true],
                ]),
                'target_practice' => 'abattoir-operator',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cassava Processing Techniques',
                'description' => 'A detailed resource on various techniques for processing cassava into different products.',
                'price' => 1200.00,
                'requires_payment' => true,
                'credo_merchant_id' => 'YOUR_CREDO_MERCHANT_ID_2',
                'form_fields' => json_encode([
                    ['label' => 'Business Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Contact Person', 'type' => 'text', 'required' => true],
                    ['label' => 'Processing Focus', 'type' => 'select', 'options' => 'Garri, Flour, Fufu, Others', 'required' => true],
                    ['label' => 'Proof of Payment', 'type' => 'file', 'required' => true],
                ]),
                'target_practice' => 'processor',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Soil Health Management Workshop',
                'description' => 'Information and registration for an upcoming workshop on improving soil health for better yields.',
                'price' => 750.00,
                'requires_payment' => true,
                'credo_merchant_id' => 'YOUR_CREDO_MERCHANT_ID_3',
                'form_fields' => json_encode([
                    ['label' => 'Full Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Email Address', 'type' => 'email'],
                    ['label' => 'LGA of Residence', 'type' => 'select', 'options' => 'Agatu, Apa, Ado, Buruku, Gboko, Guma, Gwer East, Gwer West, Katsina-Ala, Konshisha, Kwande, Logo, Makurdi, Obi, Ogbadibo, Ohimini, Oju, Tarka, Ukum, Ushongo, Vandeikya', 'required' => true],
                    ['label' => 'Payment Transaction ID', 'type' => 'text', 'required' => true],
                ]),
                'target_practice' => 'crop-farmer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Disease Prevention in Small Ruminants',
                'description' => 'A guide on common diseases affecting goats and sheep and how to prevent them.',
                'price' => 0.00,
                'requires_payment' => false,
                'credo_merchant_id' => null,
                'form_fields' => json_encode([
                    ['label' => 'Farm Name', 'type' => 'text'],
                    ['label' => 'Types of Animals', 'type' => 'checkbox', 'options' => 'Goats, Sheep'],
                    ['label' => 'Contact Number', 'type' => 'tel', 'required' => true],
                ]),
                'target_practice' => 'animal-farmer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meat Inspection Guidelines',
                'description' => 'Official guidelines and procedures for meat inspection in Benue State abattoirs.',
                'price' => 1000.00,
                'requires_payment' => true,
                'credo_merchant_id' => 'YOUR_CREDO_MERCHANT_ID_4',
                'form_fields' => json_encode([
                    ['label' => 'Abattoir License Number', 'type' => 'text', 'required' => true],
                    ['label' => 'Inspector Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Official ID Number', 'type' => 'text', 'required' => true],
                    ['label' => 'Proof of Payment', 'type' => 'file', 'required' => true],
                ]),
                'target_practice' => 'abattoir-operator',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Value Addition in Fruit Processing',
                'description' => 'Learn how to process locally grown fruits into juices, jams, and other valuable products.',
                'price' => 800.00,
                'requires_payment' => true,
                'credo_merchant_id' => 'YOUR_CREDO_MERCHANT_ID_5',
                'form_fields' => json_encode([
                    ['label' => 'Processor Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Types of Fruits Interested In', 'type' => 'checkbox', 'options' => 'Mango, Orange, Pineapple, Others'],
                    ['label' => 'Business Permit Number', 'type' => 'text'],
                    ['label' => 'Payment Reference', 'type' => 'text', 'required' => true],
                ]),
                'target_practice' => 'processor',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Integrated Pest Management for Beginners',
                'description' => 'An introductory guide to Integrated Pest Management (IPM) techniques for crop farmers.',
                'price' => 0.00,
                'requires_payment' => false,
                'credo_merchant_id' => null,
                'form_fields' => json_encode([
                    ['label' => 'Farmer ID (if applicable)', 'type' => 'text'],
                    ['label' => 'Main Crops Grown', 'type' => 'text', 'required' => true],
                    ['label' => 'Specific Pest Issues', 'type' => 'textarea'],
                ]),
                'target_practice' => 'crop-farmer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cattle Breeding Techniques Workshop',
                'description' => 'Register for a practical workshop on modern cattle breeding techniques to improve herd quality.',
                'price' => 1500.00,
                'requires_payment' => true,
                'credo_merchant_id' => 'YOUR_CREDO_MERCHANT_ID_6',
                'form_fields' => json_encode([
                    ['label' => 'Farm Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Owner Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Number of Cattle', 'type' => 'number', 'required' => true],
                    ['label' => 'Preferred Payment Method', 'type' => 'select', 'options' => 'Bank Transfer, Mobile Money', 'required' => true],
                    ['label' => 'Payment Confirmation Code', 'type' => 'text', 'required' => true],
                ]),
                'target_practice' => 'animal-farmer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}