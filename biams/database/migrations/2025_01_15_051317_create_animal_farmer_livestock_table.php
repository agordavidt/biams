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
        Schema::create('animal_farmer_livestock', function (Blueprint $table) {
            $table->id();          
            $table->foreignId('animal_farmers_id')->constrained()->onDelete('cascade');
            $table->foreignId('livestock_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_farmer_livestock');
    }
};
