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
        Schema::create('meat_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abattoir_id')->constrained();
            $table->foreignId('slaughter_operation_id')->constrained();
            $table->string('vendor_name');
            $table->string('vendor_phone')->nullable();
            $table->string('destination');
            $table->string('destination_lga');
            $table->float('quantity_kg');
            $table->dateTime('distribution_date');
            $table->enum('transportation_method', ['refrigerated', 'non_refrigerated', 'other']);
            $table->string('vehicle_number')->nullable();
            $table->enum('status', ['scheduled', 'in_transit', 'delivered', 'canceled']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meat_distributions');
    }
};
