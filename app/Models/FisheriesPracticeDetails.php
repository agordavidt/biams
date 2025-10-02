<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FisheriesPracticeDetails extends Model
{
    use HasFactory;
    
    protected $table = 'fisheries_practice_details';

    protected $fillable = [
        'farm_land_id',
        'fishing_type',
        'species_raised',
        'pond_size_sqm',
        'expected_harvest_kg',
    ];

    protected $casts = [
        'pond_size_sqm' => 'decimal:2',
        'expected_harvest_kg' => 'decimal:2',
    ];

    public function farmLand(): BelongsTo
    {
        return $this->belongsTo(FarmLand::class);
    }
}