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
        Schema::create('ante_mortem_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained()->onDelete('cascade');
            $table->foreignId('abattoir_id')->constrained();
            $table->foreignId('inspector_id')->constrained('users');
            $table->dateTime('inspection_date');
            $table->float('temperature')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->enum('general_appearance', ['normal', 'abnormal']);
            $table->boolean('is_alert')->default(true);
            $table->boolean('has_lameness')->default(false);
            $table->boolean('has_visible_injuries')->default(false);
            $table->boolean('has_abnormal_discharge')->default(false);
            $table->enum('decision', ['approved', 'rejected', 'conditional']);
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ante_mortem_inspections');
    }
};
