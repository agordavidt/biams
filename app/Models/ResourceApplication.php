<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'resource_id',
        'form_data',
        'status',
        'payment_status',
        'payment_reference',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'form_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Valid status values for the application
     */
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DELIVERED = 'delivered';

    /**
     * Get all possible status values
     *
     * @return array
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_REVIEWING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_PROCESSING,
            self::STATUS_DELIVERED,
        ];
    }

    /**
     * Get the user that owns the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the resource that this application is for.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Check if the application can be updated to a given status
     *
     * @param string $newStatus
     * @return bool
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $validTransitions = [
            self::STATUS_PENDING => [self::STATUS_REVIEWING, self::STATUS_REJECTED],
            self::STATUS_REVIEWING => [self::STATUS_APPROVED, self::STATUS_REJECTED],
            self::STATUS_APPROVED => [self::STATUS_PROCESSING],
            self::STATUS_PROCESSING => [self::STATUS_DELIVERED],
            self::STATUS_REJECTED => [],
            self::STATUS_DELIVERED => [],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    /**
     * Update the application status
     *
     * @param string $newStatus
     * @return bool
     */
    public function updateStatus(string $newStatus): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        return $this->update(['status' => $newStatus]);
    }

    /**
     * Check if the application requires payment
     *
     * @return bool
     */
    public function requiresPayment(): bool
    {
        return $this->resource->requires_payment;
    }

    /**
     * Check if payment has been completed
     *
     * @return bool
     */
    public function isPaymentCompleted(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Update payment information
     *
     * @param string $status
     * @param string $reference
     * @return bool
     */
    public function updatePayment(string $status, string $reference): bool
    {
        return $this->update([
            'payment_status' => $status,
            'payment_reference' => $reference,
        ]);
    }

    /**
     * Scope a query to only include pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include approved applications.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Get the application's status in a human-readable format
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Get the formatted creation date
     *
     * @return string
     */
    public function getSubmittedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }

    /**
     * Check if the application can be edited
     *
     * @return bool
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_REVIEWING
        ]);
    }
}