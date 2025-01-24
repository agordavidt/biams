<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class AnimalFarmer extends Model
{
    use HasFactory;

   protected $fillable = [
    'herd_size',
    'facility_type',
    'breeding_program',
    'farm_location',
    'livestock',
    'other_livestock',
];


    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}