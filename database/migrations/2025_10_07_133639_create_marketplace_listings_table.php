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
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('marketplace_categories')->onDelete('restrict');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->string('unit', 50)->nullable()->comment('kg, bags, liters, etc.');
            $table->integer('quantity')->nullable();
            $table->string('location');
            $table->string('contact', 20);
            $table->enum('status', ['draft', 'pending_review', 'active', 'sold_out', 'expired', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('view_count')->default(0);
            $table->integer('inquiry_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'expires_at']);
            $table->index(['user_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_listings');
    }
};
