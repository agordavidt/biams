<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'requires_payment',
        'credo_merchant_id', 'form_fields', 'target_practice', 'is_active'
    ];

    protected $casts = [
        'requires_payment' => 'boolean',
        'is_active' => 'boolean',
        'form_fields' => 'array',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForUserPractice(Builder $query, User $user): Builder
    {
        return $query->where(function($q) use ($user) {
            $q->where('target_practice', 'all')
              ->orWhereIn('target_practice', $this->getUserPractices($user));
        });
    }

    // Relationships
    public function applications(): HasMany
    {
        return $this->hasMany(ResourceApplication::class);
    }

    // Helper Methods
    public function isAvailableFor(string $userPractice): bool
    {
        return $this->target_practice === 'all' || $this->target_practice === $userPractice;
    }

    protected function getUserPractices(User $user): array
    {
        $practices = [];
        
        if ($user->cropFarmers()->exists()) $practices[] = 'crop-farmer';
        if ($user->animalFarmers()->exists()) $practices[] = 'animal-farmer';
        if ($user->abattoirOperators()->exists()) $practices[] = 'abattoir-operator';
        if ($user->processors()->exists()) $practices[] = 'processor';
        
        return $practices;
    }
}