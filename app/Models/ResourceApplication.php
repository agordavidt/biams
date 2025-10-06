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
    const STATUS_APPROVED = 'granted';
    const STATUS_REJECTED = 'declined';
    

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
            self::STATUS_APPROVED => 'Granted/Approved',
            self::STATUS_REJECTED => 'Declined/Rejected',
            
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
            self::STATUS_PENDING => [self::STATUS_APPROVED, self::STATUS_REJECTED],
            self::STATUS_APPROVED => [], 
            self::STATUS_REJECTED => [],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }


     public function canBeEdited(): bool
    {
      
        return $this->status === self::STATUS_PENDING;
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