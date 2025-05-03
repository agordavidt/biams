<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'requires_payment',
        'form_fields', 'target_practice', 'start_date', 'end_date', 'partner_id'
    ];

    protected $casts = [
        'requires_payment' => 'boolean',
        'form_fields' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        $today = Carbon::today();
        return $query->where(function($q) use ($today) {
            // Resource is active if:
            // 1. start_date is null or today is >= start_date AND
            // 2. end_date is null or today is <= end_date
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $today);
        })->where(function($q) use ($today) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $today);
        });
    }

    public function scopeExpired(Builder $query): Builder
    {
        $today = Carbon::today();
        return $query->whereNotNull('end_date')
                    ->where('end_date', '<', $today);
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
    
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class)->withDefault([
            'legal_name' => 'Ministry of Agriculture',
        ]);
    }

    // Helper Methods
    public function isActive(): bool
    {
        $today = Carbon::today();
        
        // Start date check: null or today is on/after start
        $isStartValid = $this->start_date === null || $today->greaterThanOrEqualTo($this->start_date);
        
        // End date check: null or today is on/before end
        $isEndValid = $this->end_date === null || $today->lessThanOrEqualTo($this->end_date);
        
        return $isStartValid && $isEndValid;
    }

    public function isExpired(): bool
    {
        return $this->end_date !== null && Carbon::today()->greaterThan($this->end_date);
    }

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