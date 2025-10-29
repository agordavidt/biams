<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();

            // Ownership - either vendor or ministry (null = ministry)
            $table->foreignId('vendor_id')
                ->nullable()
                ->constrained('vendors')
                ->nullOnDelete();

            // Keep partner for legacy support
            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners')
                ->nullOnDelete();

            // Basic resource information
            $table->string('name');
            $table->enum('type', [
                'seed',
                'fertilizer', 
                'equipment',
                'pesticide',
                'training',
                'service',
                'tractor_service',
                'other'
            ])->default('other');
            $table->text('description');
            
            // Unit & Quantity Management (nullable for services/training)
            $table->string('unit', 50)->nullable();
            $table->boolean('requires_quantity')->default(true); // False for services/training
            $table->integer('max_per_farmer')->nullable();
            $table->integer('total_stock')->nullable();
            $table->integer('available_stock')->nullable();
            
            // Pricing Structure
            $table->boolean('requires_payment')->default(false);
            $table->decimal('original_price', 10, 2)->default(0.00); // Vendor's price
            $table->decimal('subsidized_price', 10, 2)->nullable(); // Admin-set farmer price
            $table->decimal('price', 10, 2)->default(0.00); // Active price (for backward compatibility)
            $table->decimal('vendor_reimbursement', 10, 2)->nullable(); // What vendor gets paid

            // Dynamic Forms (from old system)
            $table->json('form_fields')->nullable();

            // Availability Period
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Status Workflow
            $table->enum('status', [
                'draft',        // Ministry admin creating
                'proposed',     // Vendor submitted
                'under_review', // Admin reviewing
                'approved',     // Approved but not published
                'active',       // Published and available
                'rejected',     // Rejected by admin
                'inactive',     // Unpublished
                'expired'       // Past end_date
            ])->default('draft');
            
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();

            // Review and approval tracking
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['vendor_id', 'status']);
            $table->index(['status', 'requires_payment']);
            $table->index('type');
            $table->index(['start_date', 'end_date']);
            $table->index('available_stock');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resources');
    }
};