<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'resource_id', 'form_data', 'payment_receipt_path',
        'status', 'payment_status', 'payment_reference',
    ];

    protected $casts = [
        'form_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';   
    const STATUS_DELIVERED = 'delivered';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_VERIFIED = 'verified';
    const PAYMENT_STATUS_FAILED = 'failed';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING, self::STATUS_REVIEWING, self::STATUS_APPROVED,
            self::STATUS_REJECTED, self::STATUS_DELIVERED,
        ];
    }

    public static function getPaymentStatusOptions(): array
    {
        return [
            self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PAID,
            self::PAYMENT_STATUS_VERIFIED, self::PAYMENT_STATUS_FAILED,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
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

    public function updatePaymentStatus(string $status, ?string $reference = null): bool
    {
        return $this->update([
            'payment_status' => $status,
            'payment_reference' => $reference,
        ]);
    }

    /**
     * Check if the application can still be edited (e.g., status is pending or reviewing).
     *
     * @return bool
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_REVIEWING]);
    }
}