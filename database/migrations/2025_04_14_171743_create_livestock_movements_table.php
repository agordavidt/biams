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

        if(Schema::hasTable('livestock_movements')) return;
        Schema::create('livestock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained()->onDelete('cascade');
            $table->string('from_location');
            $table->string('to_location');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time')->nullable();
            $table->string('transporter_name');
            $table->string('transporter_phone')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->enum('status', ['in_transit', 'completed', 'delayed', 'canceled']);
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_movements');
    }
};


