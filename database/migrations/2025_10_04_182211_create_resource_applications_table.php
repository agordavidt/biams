<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resource_applications', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->foreignId('farmer_id')->nullable()->constrained('farmers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Dynamic Form Data (from old system)
            $table->json('form_data')->nullable();
            
            // Quantity Management (nullable for services/training)
            $table->integer('quantity_requested')->nullable();
            $table->integer('quantity_approved')->nullable();
            $table->integer('quantity_paid')->nullable();
            $table->integer('quantity_fulfilled')->nullable();
            
            // Pricing
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            
            // Status Tracking
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'payment_pending',
                'paid',
                'fulfilled',
                'cancelled'
            ])->default('pending');
            
            // Review & Approval
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Payment Details
            $table->string('payment_reference')->nullable()->unique();
            $table->enum('payment_status', ['pending', 'verified', 'failed'])->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Fulfillment Details
            $table->foreignId('fulfilled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fulfilled_at')->nullable();
            $table->text('fulfillment_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['resource_id', 'status']);
            $table->index(['farmer_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('payment_reference');
            $table->index('payment_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resource_applications');
    }
};