<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'partner_id',
        'name', 
        'type',
        'description', 
        'unit',
        'requires_quantity',
        'max_per_farmer',
        'total_stock',
        'available_stock',
        'requires_payment',
        'original_price',
        'subsidized_price',
        'price',
        'vendor_reimbursement',
        'status',
        'rejection_reason',
        'admin_notes',
        'created_by',
        'reviewed_by',
        'reviewed_at',
        'form_fields', 
        'start_date', 
        'end_date',
    ];

    protected $casts = [
        'requires_payment' => 'boolean',
        'requires_quantity' => 'boolean',
        'form_fields' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'original_price' => 'decimal:2',
        'subsidized_price' => 'decimal:2',
        'price' => 'decimal:2',
        'vendor_reimbursement' => 'decimal:2',
        'max_per_farmer' => 'integer',
        'total_stock' => 'integer',
        'available_stock' => 'integer',
    ];

    // SCOPES
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', Carbon::today());
                    });
    }

    public function scopeAvailableForApplication(Builder $query): Builder
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', Carbon::today());
                    })
                    ->where(function($q) {
                        $q->where('requires_quantity', false)
                          ->orWhere('available_stock', '>', 0);
                    });
    }

    public function scopeMinistryResources(Builder $query): Builder
    {
        return $query->whereNull('vendor_id');
    }

    public function scopeVendorResources(Builder $query): Builder
    {
        return $query->whereNotNull('vendor_id');
    }

    public function scopeProposed(Builder $query): Builder
    {
        return $query->where('status', 'proposed');
    }

    public function scopeUnderReview(Builder $query): Builder
    {
        return $query->where('status', 'under_review');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('requires_payment', true);
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->where('requires_payment', false);
    }

    // RELATIONSHIPS
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ResourceApplication::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'resourceId');
    }

    // ACCESSORS
    public function getActivePriceAttribute(): float
    {
        // Return subsidized price if set, otherwise original price
        return $this->subsidized_price ?? $this->original_price;
    }

    public function getIsMinistryResourceAttribute(): bool
    {
        return $this->vendor_id === null;
    }

    public function getIsVendorResourceAttribute(): bool
    {
        return $this->vendor_id !== null;
    }

    public function getTotalAllocatedAttribute(): int
    {
        return $this->applications()
            ->whereIn('status', ['approved', 'paid', 'fulfilled'])
            ->sum('quantity_approved') ?? 0;
    }

    public function getUtilizationRateAttribute(): float
    {
        if (!$this->requires_quantity || $this->total_stock === 0) {
            return 0;
        }

        return ($this->total_stock - $this->available_stock) / $this->total_stock * 100;
    }

    // STATUS CHECKS
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function isAvailableForApplication(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        // For services/training (no quantity), always available if active
        if (!$this->requires_quantity) {
            return true;
        }

        // For physical resources, check stock
        return $this->available_stock > 0;
    }

    public function isExpired(): bool
    {
        return $this->end_date !== null && Carbon::today()->greaterThan($this->end_date);
    }

    public function canFarmerApply($farmerId): bool
    {
        if (!$this->isAvailableForApplication()) {
            return false;
        }

        // Check if farmer already has an active application
        $existingApplication = $this->applications()
            ->where(function($q) use ($farmerId) {
                $q->where('farmer_id', $farmerId)
                  ->orWhere('user_id', auth()->id());
            })
            ->whereIn('status', ['pending', 'approved', 'payment_pending', 'paid'])
            ->exists();

        return !$existingApplication;
    }

    // STATUS TRANSITIONS
    public function markAsApproved($reviewedBy, $subsidizedPrice = null, $vendorReimbursement = null): bool
    {
        $updateData = [
            'status' => 'approved',
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ];

        // Admin can set subsidized price during approval
        if ($subsidizedPrice !== null) {
            $updateData['subsidized_price'] = $subsidizedPrice;
            $updateData['price'] = $subsidizedPrice; // Sync for backward compatibility
        }

        // Set vendor reimbursement (how much vendor gets paid)
        if ($vendorReimbursement !== null) {
            $updateData['vendor_reimbursement'] = $vendorReimbursement;
        }

        return $this->update($updateData);
    }

    public function markAsRejected($reason, $reviewedBy): bool
    {
        return $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
        ]);
    }

    public function publish(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        return $this->update(['status' => 'active']);
    }

    public function unpublish(): bool
    {
        return $this->update(['status' => 'inactive']);
    }

    public function markUnderReview(): bool
    {
        return $this->update(['status' => 'under_review']);
    }

    // STOCK MANAGEMENT
    public function decrementStock($quantity): bool
    {
        if (!$this->requires_quantity) {
            return true;
        }

        if ($this->available_stock < $quantity) {
            return false;
        }

        return $this->decrement('available_stock', $quantity);
    }

    public function incrementStock($quantity): bool
    {
        if (!$this->requires_quantity) {
            return true;
        }

        return $this->increment('available_stock', $quantity);
    }

    // HELPER METHODS
    public function getOwnerName(): string
    {
        if ($this->vendor) {
            return $this->vendor->legal_name;
        }

        if ($this->partner) {
            return $this->partner->legal_name;
        }

        return 'Ministry of Agriculture';
    }
}