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
        Schema::create('slaughter_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abattoir_id')->constrained();
            $table->foreignId('livestock_id')->constrained();
            $table->date('slaughter_date');
            $table->time('slaughter_time');
            $table->foreignId('slaughtered_by')->constrained('users');
            $table->foreignId('supervised_by')->nullable()->constrained('users');
            $table->float('carcass_weight_kg')->nullable();
            $table->enum('meat_grade', ['premium', 'standard', 'economy', 'ungraded'])->default('ungraded');
            $table->boolean('is_halal')->default(false);
            $table->boolean('is_kosher')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slaughter_operations');
    }
};
