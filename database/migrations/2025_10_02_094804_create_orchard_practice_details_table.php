<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orchard_practice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_land_id')->unique()->constrained('farm_lands')->onDelete('cascade');
            
            $table->string('tree_type');
            $table->integer('number_of_trees')->unsigned();
            $table->enum('maturity_stage', ['seedling', 'immature', 'producing']);
            
            $table->timestamps();
            
            $table->index('tree_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orchard_practice_details');
    }
};
