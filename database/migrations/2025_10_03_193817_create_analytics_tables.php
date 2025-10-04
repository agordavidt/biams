<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Materialized view-style table for farmer demographics analytics
        Schema::create('analytics_farmer_demographics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('cascade');
            $table->date('snapshot_date')->comment('Date of this snapshot');

            // Gender breakdown
            $table->integer('total_farmers')->unsigned()->default(0);
            $table->integer('male_count')->unsigned()->default(0);
            $table->integer('female_count')->unsigned()->default(0);
            $table->integer('other_gender_count')->unsigned()->default(0);

            // Age demographics
            $table->integer('age_18_25')->unsigned()->default(0);
            $table->integer('age_26_35')->unsigned()->default(0);
            $table->integer('age_36_45')->unsigned()->default(0);
            $table->integer('age_46_55')->unsigned()->default(0);
            $table->integer('age_56_plus')->unsigned()->default(0);

            // Education levels
            $table->integer('edu_none')->unsigned()->default(0);
            $table->integer('edu_primary')->unsigned()->default(0);
            $table->integer('edu_secondary')->unsigned()->default(0);
            $table->integer('edu_tertiary')->unsigned()->default(0);
            $table->integer('edu_vocational')->unsigned()->default(0);

            // Marital status
            $table->integer('marital_single')->unsigned()->default(0);
            $table->integer('marital_married')->unsigned()->default(0);
            $table->integer('marital_divorced')->unsigned()->default(0);
            $table->integer('marital_widowed')->unsigned()->default(0);

            // Occupation breakdown
            $table->integer('occupation_full_time')->unsigned()->default(0);
            $table->integer('occupation_part_time')->unsigned()->default(0);
            $table->integer('occupation_other')->unsigned()->default(0);

            // Average household size
            $table->decimal('avg_household_size', 5, 2)->default(0);

            $table->timestamps();

            $table->unique(['lga_id', 'snapshot_date'], 'farmerdemo_lga_date_uniq');
            $table->index('snapshot_date');
        });

        // Farm production analytics
        Schema::create('analytics_farm_production', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('cascade');
            $table->date('snapshot_date');

            // Farm type distribution
            $table->integer('farms_crops')->unsigned()->default(0);
            $table->integer('farms_livestock')->unsigned()->default(0);
            $table->integer('farms_fisheries')->unsigned()->default(0);
            $table->integer('farms_orchards')->unsigned()->default(0);
            $table->integer('farms_forestry')->unsigned()->default(0);

            // Land ownership
            $table->integer('ownership_owned')->unsigned()->default(0);
            $table->integer('ownership_leased')->unsigned()->default(0);
            $table->integer('ownership_shared')->unsigned()->default(0);
            $table->integer('ownership_communal')->unsigned()->default(0);

            // Total land area by type (hectares)
            $table->decimal('total_cropland_ha', 15, 4)->default(0);
            $table->decimal('total_livestock_land_ha', 15, 4)->default(0);
            $table->decimal('total_fisheries_area_ha', 15, 4)->default(0);
            $table->decimal('total_orchard_land_ha', 15, 4)->default(0);
            $table->decimal('total_forestry_land_ha', 15, 4)->default(0);
            $table->decimal('total_land_ha', 15, 4)->default(0);

            // Average farm sizes
            $table->decimal('avg_farm_size_ha', 10, 4)->default(0);
            $table->decimal('avg_cropland_size_ha', 10, 4)->default(0);

            $table->timestamps();

            $table->unique(['lga_id', 'snapshot_date'], 'farmprod_lga_date_uniq');
            $table->index('snapshot_date');
        });

        // Crop-specific analytics
        Schema::create('analytics_crop_production', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('cascade');
            $table->string('crop_type');
            $table->date('snapshot_date');

            $table->integer('farmer_count')->unsigned()->default(0);
            $table->integer('farm_count')->unsigned()->default(0);
            $table->decimal('total_area_ha', 15, 4)->default(0);
            $table->decimal('total_expected_yield_kg', 15, 2)->default(0);
            $table->decimal('avg_yield_per_ha', 10, 2)->default(0);

            // Farming methods breakdown
            $table->integer('method_irrigation')->unsigned()->default(0);
            $table->integer('method_rain_fed')->unsigned()->default(0);
            $table->integer('method_organic')->unsigned()->default(0);
            $table->integer('method_mixed')->unsigned()->default(0);

            $table->timestamps();

            $table->unique(['lga_id', 'crop_type', 'snapshot_date'], 'crop_lga_type_date_uniq');
            $table->index(['crop_type', 'snapshot_date']);
        });

        // Livestock analytics
        Schema::create('analytics_livestock_production', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('cascade');
            $table->string('animal_type');
            $table->date('snapshot_date');

            $table->integer('farmer_count')->unsigned()->default(0);
            $table->integer('farm_count')->unsigned()->default(0);
            $table->integer('total_herd_size')->unsigned()->default(0);
            $table->decimal('avg_herd_size', 10, 2)->default(0);

            // Breeding practice breakdown
            $table->integer('practice_open_grazing')->unsigned()->default(0);
            $table->integer('practice_ranching')->unsigned()->default(0);
            $table->integer('practice_intensive')->unsigned()->default(0);
            $table->integer('practice_semi_intensive')->unsigned()->default(0);

            $table->timestamps();

            $table->unique(['lga_id', 'animal_type', 'snapshot_date'], 'livestock_lga_animal_date_uniq');
            $table->index(['animal_type', 'snapshot_date']);
        });

        // Cooperative analytics
        Schema::create('analytics_cooperative_engagement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('cascade');
            $table->date('snapshot_date');

            $table->integer('total_cooperatives')->unsigned()->default(0);
            $table->integer('farmers_in_cooperatives')->unsigned()->default(0);
            $table->integer('farmers_not_in_cooperatives')->unsigned()->default(0);
            $table->decimal('cooperative_participation_rate', 5, 2)->default(0);
            $table->decimal('avg_cooperative_size', 10, 2)->default(0);
            $table->decimal('total_cooperative_land_ha', 15, 4)->default(0);

            $table->timestamps();

            $table->unique(['lga_id', 'snapshot_date'], 'coop_lga_date_uniq');
            $table->index('snapshot_date');
        });

        // Enrollment workflow analytics
        Schema::create('analytics_enrollment_pipeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('cascade');
            $table->foreignId('enrolled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('snapshot_date');

            $table->integer('pending_review_count')->unsigned()->default(0);
            $table->integer('pending_activation_count')->unsigned()->default(0);
            $table->integer('active_count')->unsigned()->default(0);
            $table->integer('rejected_count')->unsigned()->default(0);
            $table->integer('suspended_count')->unsigned()->default(0);

            // New enrollments in period
            $table->integer('new_enrollments_today')->unsigned()->default(0);
            $table->integer('new_enrollments_week')->unsigned()->default(0);
            $table->integer('new_enrollments_month')->unsigned()->default(0);

            // Approval metrics
            $table->integer('approved_today')->unsigned()->default(0);
            $table->integer('rejected_today')->unsigned()->default(0);
            $table->decimal('approval_rate', 5, 2)->default(0);

            $table->timestamps();

            $table->unique(['lga_id', 'snapshot_date', 'enrolled_by'], 'enroll_lga_date_user_uniq');
            $table->index(['enrolled_by', 'snapshot_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_enrollment_pipeline');
        Schema::dropIfExists('analytics_cooperative_engagement');
        Schema::dropIfExists('analytics_livestock_production');
        Schema::dropIfExists('analytics_crop_production');
        Schema::dropIfExists('analytics_farm_production');
        Schema::dropIfExists('analytics_farmer_demographics');
    }
};
