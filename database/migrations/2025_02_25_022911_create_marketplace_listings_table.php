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
            $table->foreignId('category_id')->constrained('marketplace_categories');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('unit')->nullable(); 
            $table->integer('quantity')->nullable();
            $table->string('image')->nullable();
            $table->string('location'); 
            $table->enum('availability', ['available', 'sold'])->default('available');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
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
