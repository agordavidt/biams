<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResourceApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'resource_id', 'form_data', 'payment_reference',
        'status', 'payment_status'
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DELIVERED = 'delivered';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_VERIFIED = 'verified';
    const PAYMENT_STATUS_FAILED = 'failed';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    // Status Methods
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_REVIEWING => 'Reviewing',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_DELIVERED => 'Delivered',
        ];
    }

    public static function getPaymentStatusOptions(): array
    {
        return [
            self::PAYMENT_STATUS_PENDING => 'Pending',
            self::PAYMENT_STATUS_PAID => 'Paid',
            self::PAYMENT_STATUS_VERIFIED => 'Verified',
            self::PAYMENT_STATUS_FAILED => 'Failed',
        ];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $validTransitions = [
            self::STATUS_PENDING => [self::STATUS_REVIEWING, self::STATUS_REJECTED],
            self::STATUS_REVIEWING => [self::STATUS_APPROVED, self::STATUS_REJECTED],
            self::STATUS_APPROVED => [self::STATUS_DELIVERED],
            self::STATUS_REJECTED => [],
            self::STATUS_DELIVERED => [],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    public function canBeEdited(): bool
    {
        // Applications can be edited if they're not in final states
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_REVIEWING,
            self::STATUS_APPROVED
        ]);
    }

    public function updateStatus(string $newStatus): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        return $this->update(['status' => $newStatus]);
    }

    public function requiresPayment(): bool
    {
        return $this->resource->requires_payment;
    }

    public function getStatusLabel(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabel(): string
    {
        return self::getPaymentStatusOptions()[$this->payment_status] ?? $this->payment_status;
    }
}