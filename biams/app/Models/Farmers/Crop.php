<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Define many-to-many relationship with CropFarmer
    public function cropFarmers()
    {
        return $this->belongsToMany(CropFarmer::class, 'crop_farmer_crop');
    }
}