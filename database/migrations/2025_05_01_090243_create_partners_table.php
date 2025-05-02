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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->string('registration_number')->nullable();
            $table->string('organization_type');
            $table->date('establishment_date')->nullable();
            
            // Contact person details
            $table->string('contact_person_name');
            $table->string('contact_person_title')->nullable();
            $table->string('contact_person_phone');
            $table->string('contact_person_email');
            
            // Organization details
            $table->text('address');
            $table->string('website')->nullable();
            $table->text('description');
            $table->json('focus_areas');
            
            // Compliance and due diligence
            $table->string('tax_identification_number')->nullable();
            $table->string('registration_certificate')->nullable(); // Path to uploaded file
            
            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Add partner_id to resources table
        Schema::table('resources', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->after('id')
                ->constrained('partners')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropConstrainedForeignId('partner_id');
        });
        
        Schema::dropIfExists('partners');
    }
};