<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CropPracticeDetails extends Model
{
    use HasFactory;
    
    // Note: No SoftDeletes as this data is deleted when FarmLand is deleted (via migration cascade)
    protected $table = 'crop_practice_details';

    protected $fillable = [
        'farm_land_id',
        'crop_type',
        'variety',
        'expected_yield_kg',
        'farming_method',
    ];

    protected $casts = [
        'expected_yield_kg' => 'decimal:2',
    ];

    public function farmLand(): BelongsTo
    {
        return $this->belongsTo(FarmLand::class);
    }
}