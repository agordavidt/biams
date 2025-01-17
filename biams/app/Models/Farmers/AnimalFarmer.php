<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class AnimalFarmer extends Model
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
        // Demographic fields
        'livestock',
        'herd_size',
        'facility_type',
        'breeding_program',
    ];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}