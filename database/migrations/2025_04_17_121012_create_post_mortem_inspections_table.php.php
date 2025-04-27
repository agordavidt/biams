<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostMortemInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_mortem_inspections', function (Blueprint $table) {
            $table->id();        
            $table->unsignedBigInteger('livestock_id');
            $table->foreign('livestock_id')->references('id')->on('livestock')->onDelete('cascade');           
            $table->unsignedBigInteger('abattoir_id');
            $table->foreign('abattoir_id')->references('id')->on('abattoirs')->onDelete('cascade');            
            $table->unsignedBigInteger('inspector_id');
            $table->foreign('inspector_id')->references('id')->on('abattoir_staff')->onDelete('restrict');
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_mortem_inspections');
    }
}