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
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['pending','granted', 'declined'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'verified', 'failed'])
                ->nullable()->default('pending');
            $table->timestamps();
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
