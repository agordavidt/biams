<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crop_practice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_land_id')->unique()->constrained('farm_lands')->onDelete('cascade');
            
            $table->string('crop_type');
            $table->string('variety')->nullable();
            $table->decimal('expected_yield_kg', 10, 2)->nullable();
            $table->enum('farming_method', ['irrigation', 'rain_fed', 'organic', 'mixed']);
            
            $table->timestamps();
            
            $table->index('crop_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crop_practice_details');
    }
};
