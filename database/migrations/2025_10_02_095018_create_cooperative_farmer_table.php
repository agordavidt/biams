<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_farmer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade');
            $table->foreignId('cooperative_id')->constrained('cooperatives')->onDelete('cascade');
            $table->string('membership_number')->nullable();
            $table->date('joined_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->enum('membership_status', ['active', 'inactive', 'pending'])->default('active');
            $table->string('position')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['farmer_id', 'cooperative_id']);
            $table->index('membership_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_farmer');
    }
};
