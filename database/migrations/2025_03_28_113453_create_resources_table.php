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
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('requires_payment')->default(false);
            $table->enum('payment_option', ['bank_transfer', 'entrasact', 'paystack'])->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('entrasact_instruction')->nullable();
            $table->text('paystack_instruction')->nullable();
            $table->json('form_fields')->nullable();
            $table->enum('target_practice', ['crop-farmer', 'animal-farmer', 'abattoir-operator', 'processor', 'all']);
            $table->boolean('is_active')->default(true);
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
