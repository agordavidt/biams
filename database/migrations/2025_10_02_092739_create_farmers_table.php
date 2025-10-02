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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            
            // Core Identity & Authentication
            $table->string('nin')->unique()->comment('National Identification Number');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')
                  ->comment('Links to users table for authentication');
            
            // Personal Demographics
            $table->string('full_name');
            $table->string('phone_primary')->unique();
            $table->string('phone_secondary')->nullable();
            $table->string('email')->unique();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->date('date_of_birth');
            
            // Location & Administrative Data
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('restrict');
            $table->string('ward');
            $table->text('residential_address');
            
            // Geolocation for Residence
            $table->decimal('residence_latitude', 10, 8)->nullable();
            $table->decimal('residence_longitude', 11, 8)->nullable();
            
            // Socio-Economic Profile
            $table->enum('educational_level', [
                'none', 
                'primary', 
                'secondary', 
                'tertiary', 
                'vocational'
            ]);
            $table->integer('household_size')->unsigned()->default(1);
            $table->enum('primary_occupation', [
                'full_time_farmer',
                'part_time_farmer',
                'civil_servant',
                'trader',
                'artisan',
                'student',
                'other'
            ]);
            $table->string('other_occupation')->nullable();
            
            // Cooperative Linkage
            $table->foreignId('cooperative_id')->nullable()->constrained('cooperatives')->onDelete('set null');
            
            // Enrollment & Administrative Tracking
            $table->foreignId('enrolled_by')->constrained('users')->onDelete('restrict')
                  ->comment('Enrollment Officer who registered this farmer');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('LGA Admin who approved this enrollment');
            
            // Status & Workflow
            $table->enum('status', [
                'pending_lga_review',
                'pending_activation',
                'active',
                'suspended',
                'rejected'
            ])->default('pending_lga_review');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            
            // System-Generated Credentials (Cleared after first login)
            $table->string('initial_password')->nullable()
                  ->comment('Temporary storage for initial password - cleared on first login');            
            $table->boolean('password_changed')->default(false);
            
            // Metadata
            $table->json('additional_info')->nullable()
                  ->comment('Flexible field for future expansion');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for Performance
            $table->index(['lga_id', 'status']);
            $table->index('enrolled_by');
            $table->index('approved_by');
            $table->index(['residence_latitude', 'residence_longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
