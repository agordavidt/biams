<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fisheries_practice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_land_id')->unique()->constrained('farm_lands')->onDelete('cascade');
            
            $table->enum('fishing_type', ['aquaculture_pond', 'riverine', 'reservoir']);
            $table->string('species_raised');
            $table->decimal('pond_size_sqm', 10, 2)->nullable();
            $table->decimal('expected_harvest_kg', 10, 2)->nullable();
            
            $table->timestamps();
            
            $table->index('fishing_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fisheries_practice_details');
    }
};
