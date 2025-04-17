<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livestock extends Model
{

    protected $table = 'livestock';

    
    protected $fillable = [
        'tracking_id', 'species', 'breed', 'origin_location', 'origin_lga',
        'origin_state', 'owner_name', 'owner_phone', 'owner_address',
        'registered_by', 'registration_date', 'estimated_weight_kg',
        'estimated_age_months', 'gender', 'status',
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function anteMortemInspections()
    {
        return $this->hasMany(AnteMortemInspection::class);
    }

    public function postMortemInspections()
    {
        return $this->hasMany(PostMortemInspection::class);
    }

    public function slaughterOperations()
    {
        return $this->hasMany(SlaughterOperation::class);
    }

    public static function generateTrackingId()
    {
        return 'LS-' . strtoupper(uniqid());
    }
}