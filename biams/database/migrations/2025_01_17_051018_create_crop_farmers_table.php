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
        Schema::create('crop_farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');           
            $table->decimal('farm_size', 8, 2)->nullable(false); // Precision for hectares
            $table->string('farming_methods')->nullable(false);
            $table->string('seasonal_pattern')->nullable(false);
            $table->decimal('latitude', 6, 4)->nullable(false); // Suitable for geolocation
            $table->decimal('longitude', 7, 4)->nullable(false);
            $table->string('farm_location')->nullable(false);
            $table->string('crop')->nullable(false);
            $table->string('other_crop')->nullable(true); // Only if "Other" is selected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_farmers');
    }
};
