<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Processor extends Model
{
    use HasFactory;

      protected $fillable = [
        'user_id',
        'processed_items',
        'processing_capacity',
        'equipment_type',
        'equipment_specs',
    ];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
