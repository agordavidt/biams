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
        Schema::create('hygiene_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abattoir_id')->constrained();
            $table->foreignId('inspector_id')->constrained('users');
            $table->date('inspection_date');
            $table->time('inspection_time');
            $table->boolean('facility_clean')->default(true);
            $table->boolean('equipment_sanitized')->default(true);
            $table->boolean('staff_hygiene_compliant')->default(true);
            $table->boolean('water_supply_adequate')->default(true);
            $table->boolean('drainage_adequate')->default(true);
            $table->boolean('waste_disposal_adequate')->default(true);
            $table->boolean('pest_control_adequate')->default(true);
            $table->integer('overall_score');
            $table->enum('rating', ['excellent', 'good', 'satisfactory', 'poor', 'critical']);
            $table->text('issues_identified')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hygiene_inspections');
    }
};
