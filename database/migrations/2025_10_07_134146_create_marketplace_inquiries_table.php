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
        Schema::create('marketplace_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('marketplace_listings')->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_phone', 20);
            $table->string('buyer_email')->nullable();
            $table->text('message');
            $table->string('buyer_ip', 45)->nullable();
            $table->enum('status', ['new', 'contacted', 'converted', 'archived'])->default('new');
            $table->timestamp('contacted_at')->nullable();
            $table->timestamps();
            
            $table->index(['listing_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_inquiries');
    }
};
