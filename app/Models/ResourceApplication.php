<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceApplication extends Model
{
    use HasFactory, SoftDeletes;

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAID = 'paid';
    const STATUS_FULFILLED = 'fulfilled';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_VERIFIED = 'verified';
    const PAYMENT_STATUS_FAILED = 'failed';

    protected $fillable = [
        'resource_id',
        'farmer_id',
        'user_id',
        'form_data',
        'quantity_requested',
        'quantity_approved',
        'quantity_paid',
        'quantity_fulfilled',
        'unit_price',
        'total_amount',
        'amount_paid',
        'status',
        'reviewed_by',
        'processed_by',
        'reviewed_at',
        'processed_at',
        'rejection_reason',
        'admin_notes',
        'payment_reference',
        'payment_status',
        'paid_at',
        'fulfilled_by',
        'fulfilled_at',
        'fulfillment_notes',
    ];

    protected $casts = [
        'form_data' => 'array',
        'reviewed_at' => 'datetime',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'quantity_requested' => 'integer',
        'quantity_approved' => 'integer',
        'quantity_paid' => 'integer',
        'quantity_fulfilled' => 'integer',
    ];

    // RELATIONSHIPS
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

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function fulfilledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fulfilled_by');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_reference', 'reference');
    }

    // SCOPES
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopePaymentPending($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeFulfilled($query)
    {
        return $query->where('status', self::STATUS_FULFILLED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // STATUS CHECKS
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isPaymentPending(): bool
    {
        return $this->status === self::STATUS_PAYMENT_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isFulfilled(): bool
    {
        return $this->status === self::STATUS_FULFILLED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // BUSINESS LOGIC
    public function canBePaid(): bool
    {
        return ($this->isApproved() || $this->isPaymentPending()) 
               && $this->resource->requires_payment
               && $this->getPendingPaymentAmount() > 0;
    }

    public function canBeFulfilled(): bool
    {
        return $this->isPaid();
    }

    public function canBeCancelled(): bool
    {
        return $this->isPending() || $this->isPaymentPending();
    }

    public function canBeApproved(): bool
    {
        return $this->isPending();
    }

    public function canBeRejected(): bool
    {
        return $this->isPending();
    }

    public function canTransitionTo($newStatus): bool
    {
        $validTransitions = [
            self::STATUS_PENDING => [self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_CANCELLED],
            self::STATUS_APPROVED => [self::STATUS_PAYMENT_PENDING, self::STATUS_PAID, self::STATUS_FULFILLED],
            self::STATUS_PAYMENT_PENDING => [self::STATUS_PAID, self::STATUS_CANCELLED],
            self::STATUS_PAID => [self::STATUS_FULFILLED],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    // CALCULATED ATTRIBUTES
    public function getRemainingQuantityAttribute(): int
    {
        if (!$this->resource->requires_quantity) {
            return 0;
        }

        return ($this->quantity_approved ?? $this->quantity_requested) - ($this->quantity_fulfilled ?? 0);
    }

    public function getPendingPaymentAmount(): float
    {
        if (!$this->resource->requires_payment) {
            return 0;
        }

        if ($this->isApproved() || $this->isPaymentPending()) {
            $totalAmount = $this->total_amount ?? ($this->quantity_approved * $this->unit_price);
            return $totalAmount - ($this->amount_paid ?? 0);
        }

        return 0;
    }

    public function getPaymentStatusText(): string
    {
        if (!$this->resource->requires_payment) {
            return 'Not Required';
        }

        if ($this->isPaid()) {
            return 'Paid';
        }

        if ($this->isApproved() || $this->isPaymentPending()) {
            return 'Payment Pending';
        }

        return 'Not Available';
    }

    // ACTION METHODS
    public function markAsApproved(int $reviewedBy, int $quantityApproved = null): bool
    {
        $data = [
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ];

        // Handle quantity for resources that require it
        if ($this->resource->requires_quantity) {
            $approvedQty = $quantityApproved ?? $this->quantity_requested;
            $data['quantity_approved'] = $approvedQty;
            $data['total_amount'] = $approvedQty * $this->unit_price;
        }

        // Determine next status based on payment requirement
        if ($this->resource->requires_payment) {
            $data['status'] = self::STATUS_PAYMENT_PENDING;
        } else {
            // For free resources, mark as approved and ready for fulfillment
            $data['status'] = self::STATUS_APPROVED;
        }

        return $this->update($data);
    }

    public function markAsRejected(string $reason, int $reviewedBy): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
        ]);
    }

    public function markAsPaid(float $amountPaid, string $paymentReference, int $quantityPaid = null): bool
    {
        $data = [
            'status' => self::STATUS_PAID,
            'amount_paid' => $amountPaid,
            'payment_reference' => $paymentReference,
            'payment_status' => self::PAYMENT_STATUS_VERIFIED,
            'paid_at' => now(),
        ];

        if ($this->resource->requires_quantity && $quantityPaid) {
            $data['quantity_paid'] = $quantityPaid;
        }

        return $this->update($data);
    }

    public function markAsFulfilled(int $fulfilledBy, string $notes = null, int $quantityFulfilled = null): bool
    {
        $data = [
            'status' => self::STATUS_FULFILLED,
            'fulfilled_by' => $fulfilledBy,
            'fulfilled_at' => now(),
            'fulfillment_notes' => $notes,
        ];

        if ($this->resource->requires_quantity) {
            $data['quantity_fulfilled'] = $quantityFulfilled ?? $this->quantity_paid ?? $this->quantity_approved;
        }

        return $this->update($data);
    }

    // HELPER METHODS
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PAYMENT_PENDING => 'Awaiting Payment',
            self::STATUS_PAID => 'Payment Confirmed',
            self::STATUS_FULFILLED => 'Fulfilled',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_APPROVED => 'badge-info',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_PAYMENT_PENDING => 'badge-primary',
            self::STATUS_PAID => 'badge-success',
            self::STATUS_FULFILLED => 'badge-dark',
            self::STATUS_CANCELLED => 'badge-secondary',
            default => 'badge-light',
        };
    }
}