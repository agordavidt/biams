<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AbattoirOperator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',       
        'facility_type',
        'facility_specs',
        'operational_capacity',
        'certifications',
    ];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


