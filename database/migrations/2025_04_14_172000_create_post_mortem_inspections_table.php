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
        Schema::create('post_mortem_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained()->onDelete('cascade');
            $table->foreignId('abattoir_id')->constrained();
            $table->foreignId('inspector_id')->constrained('users');
            $table->dateTime('inspection_date');
            $table->boolean('carcass_normal')->default(true);
            $table->boolean('organs_normal')->default(true);
            $table->boolean('lymph_nodes_normal')->default(true);
            $table->boolean('has_parasites')->default(false);
            $table->boolean('has_disease_signs')->default(false);
            $table->text('abnormality_details')->nullable();
            $table->enum('decision', ['fit_for_consumption', 'unfit_for_consumption', 'partially_fit']);
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('stamp_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_mortem_inspections');
    }
};
