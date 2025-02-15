<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'requires_payment',
        'form_fields',
        'target_practice',
        'is_active'
    ];

    protected $casts = [
        'requires_payment' => 'boolean',
        'is_active' => 'boolean',
        'form_fields' => 'array',
        
    ];



    public function applications()
    {
        return $this->hasMany(ResourceApplication::class);
    }

    // Helper method to check if a resource is available for a user's practice
    public function isAvailableFor($userPractice)
    {
        return $this->target_practice === 'all' || $this->target_practice === $userPractice;
    }
}
