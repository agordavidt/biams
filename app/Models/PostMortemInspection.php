<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMortemInspection extends Model
{
    protected $fillable = [
        'livestock_id', 'abattoir_id', 'inspector_id', 'inspection_date',
        'carcass_normal', 'organs_normal', 'lymph_nodes_normal', 'has_parasites',
        'has_disease_signs', 'abnormality_details', 'decision', 'rejection_reason',
        'notes', 'stamp_number',
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
        'carcass_normal' => 'boolean',
        'organs_normal' => 'boolean',
        'lymph_nodes_normal' => 'boolean',
        'has_parasites' => 'boolean',
        'has_disease_signs' => 'boolean',
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