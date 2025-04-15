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
        Schema::create('economic_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abattoir_id')->constrained();
            $table->date('report_date');
            $table->enum('report_type', ['daily', 'weekly', 'monthly', 'quarterly', 'annual']);
            $table->integer('total_slaughters');
            $table->float('total_meat_kg');
            $table->decimal('estimated_revenue', 12, 2);
            $table->integer('direct_employment');
            $table->integer('indirect_employment')->nullable();
            $table->decimal('tax_contribution', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_reports');
    }
};
