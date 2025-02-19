<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class CropFarmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'farm_size',
        'farming_methods',
        'seasonal_pattern',
        'latitude',
        'longitude',
        'farm_location',
        'crop',
        'other_crop',
        'status',
        'rejection_comments',
    ];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }   
    
}


