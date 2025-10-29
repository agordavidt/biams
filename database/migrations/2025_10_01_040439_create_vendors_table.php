<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            
            // Company Information
            $table->string('legal_name');
            $table->string('registration_number')->nullable();
            $table->string('organization_type');
            $table->date('establishment_date')->nullable();
            
            // Contact Information
            $table->string('contact_person_name');
            $table->string('contact_person_title')->nullable();
            $table->string('contact_person_phone');
            $table->string('contact_person_email');
            $table->text('address');
            $table->string('website')->nullable();
            $table->text('description');
            
            // Business Details
            $table->json('focus_areas');
            $table->string('tax_identification_number')->nullable();
            $table->string('registration_certificate')->nullable();
            
            // Banking Information
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit Fields
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};