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
            $table->unsignedBigInteger('abattoir_id');
            $table->foreign('abattoir_id')->references('id')->on('abattoirs')->onDelete('restrict'); 
            
            $table->unsignedBigInteger('livestock_id');
            $table->foreign('livestock_id')
                  ->references('id')
                  ->on('livestock')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('slaughtered_by');
            $table->foreign('slaughtered_by')
                  ->references('id')
                  ->on('abattoir_staff')
                  ->onDelete('restrict'); 

            // Foreign key for the staff who supervised the slaughter (nullable)
            $table->unsignedBigInteger('supervised_by')->nullable();
            $table->foreign('supervised_by')
                  ->references('id')
                  ->on('abattoir_staff')
                  ->onDelete('set null'); // Or 'restrict' or leave as is if no action on delete

            $table->date('slaughter_date');
            $table->time('slaughter_time');
            $table->float('carcass_weight_kg')->nullable();
            $table->enum('meat_grade', ['premium', 'standard', 'economy', 'ungraded']);           
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

