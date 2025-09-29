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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('businessName');
            $table->string('reference')->unique();
            $table->decimal('transAmount', 15, 2);
            $table->decimal('transFee', 15, 2);
            $table->decimal('transTotal', 15, 2);
            $table->timestamp('transDate');
            $table->decimal('settlementAmount', 15, 2);
            $table->string('status');
            $table->text('statusMessage')->nullable();
            $table->unsignedBigInteger('customerId');
            $table->unsignedBigInteger('resourceId');
            $table->unsignedBigInteger('resourceOwnerId');
            $table->string('channelId');
            $table->string('currencyCode', 3);
            $table->timestamps();

            $table->foreign('customerId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('resourceOwnerId')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['customerId', 'transDate']);
            $table->index(['resourceOwnerId', 'transDate']);
            $table->index('status');
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
