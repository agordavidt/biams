<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livestock_practice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_land_id')->unique()->constrained('farm_lands')->onDelete('cascade');
            
            $table->string('animal_type');
            $table->integer('herd_flock_size')->unsigned();
            $table->enum('breeding_practice', ['open_grazing', 'ranching', 'intensive', 'semi_intensive']);
            
            $table->timestamps();
            
            $table->index('animal_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livestock_practice_details');
    }
};
