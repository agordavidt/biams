<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaughterOperation extends Model
{
    protected $fillable = [
        'abattoir_id', 'livestock_id', 'slaughter_date', 'slaughter_time',
        'slaughtered_by', 'supervised_by', 'carcass_weight_kg', 'meat_grade',
        'is_halal', 'is_kosher', 'notes',
    ];

    protected $casts = [
        'slaughter_date' => 'date',
        'is_halal' => 'boolean',
        'is_kosher' => 'boolean',
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
        return $this->belongsTo(User::class, 'slaughtered_by');
    }

    public function supervisedBy()
    {
        return $this->belongsTo(User::class, 'supervised_by');
    }
}