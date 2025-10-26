<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'requires_payment',
        'form_fields', 
        'start_date', 
        'end_date', 
        'partner_id'
    ];

    protected $casts = [
        'requires_payment' => 'boolean',
        'form_fields' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Scopes
    // public function scopeActive(Builder $query): Builder
    // {
    //     $today = Carbon::today();
    //     return $query->where(function($q) use ($today) {
    //         // Resource is active if:
    //         // 1. start_date is null or today is >= start_date AND
    //         // 2. end_date is null or today is <= end_date
    //         $q->whereNull('start_date')
    //           ->orWhere('start_date', '<=', $today);
    //     })->where(function($q) use ($today) {
    //         $q->whereNull('end_date')
    //           ->orWhere('end_date', '>=', $today);
    //     });
    // }

    public function scopeExpired(Builder $query): Builder
    {
        $today = Carbon::today();
        return $query->whereNotNull('end_date')
                    ->where('end_date', '<', $today);
    }

    // Relationships
    
    
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class)->withDefault([
            'legal_name' => 'Ministry of Agriculture',
        ]);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'resourceId');
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


    /**
 * Get all applications for this resource.
 */
public function applications(): HasMany
{
    return $this->hasMany(ResourceApplication::class);
}

/**
 * Get pending applications for this resource.
 */
public function pendingApplications(): HasMany
{
    return $this->applications()->where('status', 'pending');
}

/**
 * Get approved applications for this resource.
 */
public function approvedApplications(): HasMany
{
    return $this->applications()->where('status', 'approved');
}

/**
 * Check if resource is available for applications.
 */
public function isAvailableForApplication(): bool
{
    return $this->status === 'active' && $this->available_stock > 0;
}

/**
 * Check if a specific farmer can apply for this resource.
 */
public function canFarmerApply($farmerId): bool
{
    if (!$this->isAvailableForApplication()) {
        return false;
    }

    // Check if farmer already has an active application
    $existingApplication = $this->applications()
        ->where('farmer_id', $farmerId)
        ->whereIn('status', ['pending', 'approved', 'paid'])
        ->exists();

    return !$existingApplication;
}

/**
 * Get total quantity allocated to farmers.
 */
public function getTotalAllocatedAttribute(): int
{
    return $this->applications()
        ->whereIn('status', ['approved', 'paid', 'fulfilled'])
        ->sum('quantity_approved');
}

/**
 * Scope for active resources.
 */
public function scopeActive($query)
{
    return $query->where('status', 'active');
}

/**
 * Scope for resources available for application.
 */
public function scopeAvailableForApplication($query)
{
    return $query->where('status', 'active')
        ->where('available_stock', '>', 0);
}

}