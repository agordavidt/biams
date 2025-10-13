<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FarmLand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farmer_id',
        'name',
        'farm_type',
        'total_size_hectares',
        'ownership_status',
        'geolocation_geojson',
        'farm_photo', 
    ];

    protected $casts = [
        'total_size_hectares' => 'decimal:4',
    ];

    // ==================== Relationships ====================

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    // Dynamic Relationship to Practice Details  
    public function practiceDetails()
    {
        switch ($this->farm_type) {
            case 'crops':
                return $this->hasOne(CropPracticeDetails::class);
            case 'livestock':
                return $this->hasOne(LivestockPracticeDetails::class);
            case 'fisheries':
                return $this->hasOne(FisheriesPracticeDetails::class);
            case 'orchards':
                return $this->hasOne(OrchardPracticeDetails::class);
            default:
                // Return null or handle the case appropriately
                return null;
        }
    }

    // Individual practice detail relationships
    public function cropPracticeDetails(): HasOne
    {
        return $this->hasOne(CropPracticeDetails::class);
    }

    public function livestockPracticeDetails(): HasOne
    {
        return $this->hasOne(LivestockPracticeDetails::class);
    }

    public function fisheriesPracticeDetails(): HasOne
    {
        return $this->hasOne(FisheriesPracticeDetails::class);
    }

    public function orchardPracticeDetails(): HasOne
    {
        return $this->hasOne(OrchardPracticeDetails::class);
    }

    
}