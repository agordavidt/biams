<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivestockPracticeDetails extends Model
{
    use HasFactory;
    
    protected $table = 'livestock_practice_details';

    protected $fillable = [
        'farm_land_id',
        'animal_type',
        'herd_flock_size',
        'breeding_practice',
    ];

    protected $casts = [
        'herd_flock_size' => 'integer',
    ];

    public function farmLand(): BelongsTo
    {
        return $this->belongsTo(FarmLand::class);
    }
}