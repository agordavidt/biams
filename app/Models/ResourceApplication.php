<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resource_id',
        'farmer_id',
        'user_id',
        'quantity_requested',
        'quantity_approved',
        'quantity_paid',
        'quantity_fulfilled',
        'unit_price',
        'total_amount',
        'amount_paid',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'payment_reference',
        'paid_at',
        'fulfilled_by',
        'fulfilled_at',
        'fulfillment_notes',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'paid_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    // Relationships
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function fulfilledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fulfilled_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeFulfilled($query)
    {
        return $query->where('status', 'fulfilled');
    }

    public function scopeForFarmer($query, $farmerId)
    {
        return $query->where('farmer_id', $farmerId);
    }

    public function scopeForResource($query, $resourceId)
    {
        return $query->where('resource_id', $resourceId);
    }

    // Helper Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isFulfilled(): bool
    {
        return $this->status === 'fulfilled';
    }

    public function canBePaid(): bool
    {
        return in_array($this->status, ['approved', 'payment_pending']);
    }

    public function canBeFulfilled(): bool
    {
        return $this->status === 'paid';
    }

    public function getRemainingQuantityAttribute(): int
    {
        return ($this->quantity_approved ?? $this->quantity_requested) - ($this->quantity_fulfilled ?? 0);
    }
}