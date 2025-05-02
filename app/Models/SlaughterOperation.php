<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaughterOperation extends Model
{
    protected $fillable = [
        'abattoir_id', 'livestock_id', 'slaughter_date', 'slaughter_time',
        'slaughtered_by', 'supervised_by', 'carcass_weight_kg', 'meat_grade',
        'notes',
    ];
    
    protected $casts = [
        'slaughter_date' => 'date',
    ];

    public function abattoir()
    {
        return $this->belongsTo(Abattoir::class);
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function slaughteredBy()
    {
        return $this->belongsTo(AbattoirStaff::class, 'slaughtered_by');
    }

    public function supervisedBy()
    {
        return $this->belongsTo(AbattoirStaff::class, 'supervised_by');
    }
}