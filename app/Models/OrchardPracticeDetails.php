<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrchardPracticeDetails extends Model
{
    use HasFactory;
    
    protected $table = 'orchard_practice_details';

    protected $fillable = [
        'farm_land_id',
        'tree_type',
        'number_of_trees',
        'maturity_stage',
    ];

    protected $casts = [
        'number_of_trees' => 'integer',
    ];

    public function farmLand(): BelongsTo
    {
        return $this->belongsTo(FarmLand::class);
    }
}