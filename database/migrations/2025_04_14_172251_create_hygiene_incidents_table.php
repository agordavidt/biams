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
        Schema::create('hygiene_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abattoir_id')->constrained();
            $table->foreignId('reported_by')->constrained('users');
            $table->dateTime('incident_date');
            $table->string('incident_type');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->enum('status', ['reported', 'investigating', 'resolved', 'closed']);
            $table->text('action_taken')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hygiene_incidents');
    }
};
