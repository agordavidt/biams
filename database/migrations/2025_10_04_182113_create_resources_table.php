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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('vendor_id')
                ->nullable()
                ->constrained('vendors')
                ->nullOnDelete();

            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('requires_payment')->default(false);

            // Vendor-specific fields
            $table->decimal('vendor_reimbursement_price', 10, 2)->nullable();

            // Type and status management
            $table->enum('status', [
                'proposed',
                'under_review',
                'approved',
                'active',
                'rejected',
                'inactive'
            ])->default('proposed');

            $table->text('rejection_reason')->nullable();

            // Reviewed by
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            // Additional data
            $table->json('form_fields')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
