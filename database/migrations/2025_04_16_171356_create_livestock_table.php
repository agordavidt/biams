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
        Schema::create('livestock', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_id')->unique();
            $table->enum('species', ['cattle', 'goat', 'sheep', 'pig', 'other']);
            $table->string('breed')->nullable();
            $table->string('origin_location');
            $table->string('origin_lga');
            $table->string('origin_state')->default('Benue');
            $table->string('owner_name');
            $table->string('owner_phone')->nullable();
            $table->string('owner_address')->nullable();
            $table->foreignId('registered_by')->constrained('users');
            $table->date('registration_date');
            $table->float('estimated_weight_kg')->nullable();
            $table->integer('estimated_age_months')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->enum('status', ['registered', 'inspected', 'approved', 'rejected', 'slaughtered']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock');
    }
};
