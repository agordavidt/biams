<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Administrative Scope (Polymorphic Relationship)
            // Links a user to their LGA, Department, or Agency.
            $table->nullableMorphs('administrative'); // Creates administrative_id and administrative_type

            $table->enum('status', ['pending', 'onboarded', 'rejected'])->default('pending')->comment('User onboarding workflow status');
            $table->text('rejection_reason')->nullable();
            
            $table->rememberToken();
            $table->timestamps();

            // Index for faster lookups based on scope
            $table->index(['administrative_id', 'administrative_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
