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
        Schema::create('abattoirs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->string('license_number')->unique();
            $table->string('address');
            $table->string('lga');
            $table->string('gps_latitude')->nullable();
            $table->string('gps_longitude')->nullable();
            $table->integer('capacity');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abattoirs');
    }
};
