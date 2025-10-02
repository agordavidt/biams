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
        Schema::create('cooperatives', function (Blueprint $table) {
            $table->id();
            
            // Core Identity & Compliance
            $table->string('registration_number')->unique()->comment('Unique Government/Association ID');
            $table->string('name')->comment('Cooperative Name');
            
            // Contact Details
            $table->string('contact_person')->nullable()->comment('Name of the primary official');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            
            // Scale & Activity
            $table->integer('total_member_count')->unsigned()->default(0)->comment('Number of members reported by the cooperative');
            $table->decimal('total_land_size', 15, 2)->nullable()->comment('Aggregate land managed by the cooperative (ha)');
            $table->json('primary_activities')->nullable()->comment('Input procurement, processing, marketing, etc. (Multi-Select)');
            
            // Metadata
            $table->foreignId('lga_id')->nullable()->constrained('lgas')->onDelete('set null')
                  ->comment('Primary LGA for the cooperative');
            $table->foreignId('registered_by')->constrained('users')->onDelete('restrict')
                  ->comment('The Enrollment Agent or Admin who registered the cooperative');

            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('lga_id');
            $table->index('registration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperatives');
    }
};
