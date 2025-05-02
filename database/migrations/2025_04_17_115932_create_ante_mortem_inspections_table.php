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
            $table->unsignedBigInteger('livestock_id');
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('cascade');
            $table->unsignedBigInteger('abattoir_id');
            $table->foreign('abattoir_id')->references('id')->on('abattoirs')->onDelete('cascade'); 
            $table->unsignedBigInteger('inspector_id');
            $table->foreign('inspector_id')->references('id')->on('abattoir_staff')->onDelete('restrict'); 
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

















