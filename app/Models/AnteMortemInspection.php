<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnteMortemInspection extends Model
{
    protected $fillable = [
        'livestock_id', 'abattoir_id', 'inspector_id', 'inspection_date',
        'temperature', 'heart_rate', 'respiratory_rate', 'general_appearance',
        'is_alert', 'has_lameness', 'has_visible_injuries', 'has_abnormal_discharge',
        'decision', 'rejection_reason', 'notes',
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
        'is_alert' => 'boolean',
        'has_lameness' => 'boolean',
        'has_visible_injuries' => 'boolean',
        'has_abnormal_discharge' => 'boolean',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function abattoir()
    {
        return $this->belongsTo(Abattoir::class);
    }

    public function inspector()
    {
        return $this->belongsTo(AbattoirStaff::class, 'inspector_id');
    }
}