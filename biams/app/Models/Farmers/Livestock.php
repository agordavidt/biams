<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Define many-to-many relationship with AnimalFarmer
    public function animalFarmers()
    {
        return $this->belongsToMany(AnimalFarmer::class, 'animal_farmer_livestock');
    }
}