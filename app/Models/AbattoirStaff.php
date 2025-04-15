<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbattoirStaff extends Model
{
    protected $fillable = [
        'abattoir_id', 'user_id', 'role', 'start_date', 'end_date', 'is_active',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}