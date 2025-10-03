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
        Schema::create('farm_lands', function (Blueprint $table) {
            $table->id(); // Farm ID (Primary Key)
            
            // Linkage
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade')
                  ->comment('Links the farm back to the Farmer Profile');
            
            // Plot Details
            $table->string('name')->comment('E.g., "Home Plot," "River Field"');
            $table->enum('farm_type', ['crops', 'livestock', 'fisheries', 'orchards', 'forestry'])
                  ->comment('Used to dynamically load the next sub-form');
            $table->decimal('total_size_hectares', 10, 4)
                  ->comment('The registered size of this specific plot (ha)');
            $table->enum('ownership_status', ['owned', 'leased', 'shared', 'communal']);
            
            // Geospatial Data          
            $table->longText('geolocation_geojson')->nullable()
                  ->comment('Actual farm boundaries (polygon) or center point (point) as GeoJSON string');
            $table->string('farm_photo')->nullable()->comment('Path to farm photo');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('farmer_id');
            $table->index('farm_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_lands');
    }
};
