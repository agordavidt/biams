<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_reference',
        'amount',
        'start_date',
        'end_date',
        'status',
        'payment_method',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'payment_details' => 'array', // ADD THIS CAST
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'paid')
            ->where('end_date', '>', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'paid')
            ->where('end_date', '<', now());
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'paid' && $this->end_date && $this->end_date->isFuture();
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->is_active) {
            return 0;
        }
        
        return now()->diffInDays($this->end_date, false);
    }

    // Methods
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'start_date' => now(),
            'end_date' => now()->addDays(365),
        ]);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }

    public function isExpiring($days = 30)
    {
        return $this->is_active && $this->days_remaining <= $days;
    }

    /**
     * Check if user can create listings (has active subscription)
     */
    public static function userCanList($userId)
    {
        return static::where('user_id', $userId)
            ->active()
            ->exists();
    }
}