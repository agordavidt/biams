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
        Schema::create('resource_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            $table->json('form_data');
            $table->enum('status', ['pending', 'reviewing', 'approved', 'rejected', 'processing', 'delivered'])->default('pending');
            $table->string('payment_status')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();
            
            // Ensure one application per user per resource
            $table->unique(['user_id', 'resource_id']);


            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_applications');
    }
};

