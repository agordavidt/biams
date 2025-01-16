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
        'phone',
        'dob',
        'gender',
        'education',
        'household_size',
        'dependents',
        'income_level',
        'lga',
        'farm_size',
        'farming_methods',
        'crops',
        'seasonal_pattern',
        'latitude',
        'longitude',
    ];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define many-to-many relationship with Crop
    public function crops()
    {
        return $this->belongsToMany(Crop::class, 'crop_farmer_crop');
    }
}


