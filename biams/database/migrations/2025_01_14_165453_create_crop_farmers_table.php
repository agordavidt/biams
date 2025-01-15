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
            // $table->foreignId('crop_id')->constrained()->onDelete('cascade');         
            
            // Demographic fields           
            $table->string('phone')->nullable(false);
            $table->date('dob')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->string('education')->nullable(false);
            $table->integer('household_size')->nullable(false);
            $table->integer('dependents')->nullable(false);
            $table->string('income_level')->nullable(false);
            $table->string('lga')->nullable(false);

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
