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
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // Email used in login attempt
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // User if login successful
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable();
            $table->string('platform')->nullable(); // Windows, macOS, Linux, iOS, Android
            $table->string('location')->nullable(); // Country/City if available
            $table->enum('status', ['success', 'failed', 'blocked'])->default('failed');
            $table->text('failure_reason')->nullable(); // Password incorrect, account locked, etc.
            $table->boolean('is_suspicious')->default(false); // Flag for suspicious activity
            $table->json('metadata')->nullable(); // Additional data like geolocation, etc.
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['email', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['is_suspicious', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
}; 