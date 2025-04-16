<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abattoir extends Model
{
    protected $fillable = [
        'name', 'registration_number', 'license_number', 'address', 'lga',
        'gps_latitude', 'gps_longitude', 'capacity', 'status', 'description',
    ];

    public function staff()
    {
        return $this->hasMany(AbattoirStaff::class);
    }

    public function slaughterOperations()
    {
        return $this->hasMany(SlaughterOperation::class);
    }
}