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
        Schema::create('processors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('processed_items')->nullable(); 
            $table->decimal('processing_capacity', 8, 1)->nullable(false); 
            $table->string('equipment_type')->nullable(false); 
            $table->text('equipment_specs')->nullable(false);
            $table->timestamps();
           
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processors');
    }
};
