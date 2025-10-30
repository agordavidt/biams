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
        
        Schema::create('agent_resource_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('agent_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            
            $table->foreign('resource_id')
                    ->references('id')
                    ->on('resources')
                    ->onDelete('cascade');
            
            $table->foreign('assigned_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');

            // Prevent duplicate assignments
            $table->unique(['agent_id', 'resource_id'], 'agent_resource_unique');

            // Indexes for performance
            $table->index('agent_id');
            $table->index('resource_id');
            $table->index('is_active');
        });

           
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_resource_assignments');
    }
};