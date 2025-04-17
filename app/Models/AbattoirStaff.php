<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbattoirStaff extends Model
{
    protected $fillable = [
        'abattoir_id', 'name', 'email', 'phone', 'address', 'role',
        'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function abattoir()
    {
        return $this->belongsTo(Abattoir::class);
    }

    public function anteMortemInspections()
    {
        return $this->hasMany(AnteMortemInspection::class, 'inspector_id');
    }

    public function postMortemInspections()
    {
        return $this->hasMany(PostMortemInspection::class, 'inspector_id');
    }

    public function slaughterOperations()
    {
        return $this->hasMany(SlaughterOperation::class, 'slaughtered_by');
    }

    public function supervisedOperations()
    {
        return $this->hasMany(SlaughterOperation::class, 'supervised_by');
    }
}