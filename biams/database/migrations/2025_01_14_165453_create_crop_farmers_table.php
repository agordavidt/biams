<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crop_farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');         
            // Demographic fields
            $table->string('phone_number');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('education_level', [
                'No formal school',
                'Primary school',
                'Secondary school',
                'Undergraduate',
                'Graduate',
                'Post Graduate'
            ]);
            $table->integer('household_size');
            $table->integer('dependents');
            $table->enum('income_level', [
                'Less than N100,000',
                'N100,001 - N250,000',
                'N250,001 - N500,000',
                'N500,001 - N1,000,000',
                'Above N1,000,000'
            ]);
            $table->enum('local_government_area', [
                'Ado', 'Agatu', 'Apa', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West', 
                'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 
                'Oju', 'Ohimini', 'Okpokwu', 'Otpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya' 
            ]);
             // Crop Farmer  fields   
            $table->decimal('farm_size', 8, 1)->nullable(false); 
            $table->string('farming_methods')->nullable(false); 
            $table->json('crops')->nullable(); 
            $table->string('seasonal_pattern')->nullable(false); 
            $table->decimal('latitude', 10, 7)->nullable(false); 
            $table->decimal('longitude', 10, 7)->nullable(false);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_farmers');
    }
};
