<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Farmer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nin',
        'user_id',
        'full_name',
        'phone_primary',
        'phone_secondary',
        'email',
        'gender',
        'marital_status',
        'date_of_birth',
        'lga_id',
        'ward',
        'residential_address',
        'residence_latitude',
        'residence_longitude',
        'educational_level',
        'household_size',
        'primary_occupation',
        'other_occupation',
        'cooperative_id',
        'enrolled_by',
        'approved_by',
        'status',
        'rejection_reason',
        'approved_at',
        'activated_at',
        'initial_password',
        'password_changed',
        'additional_info',
    ];

    protected $hidden = [
        'initial_password',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'approved_at' => 'datetime',
        'activated_at' => 'datetime',
        'household_size' => 'integer',
        'password_changed' => 'boolean',
        'additional_info' => 'array',
        'residence_latitude' => 'decimal:8',
        'residence_longitude' => 'decimal:8',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate initial password when farmer is created
        static::creating(function ($farmer) {
            if (empty($farmer->initial_password)) {
                $farmer->initial_password = Str::random(12); // Secure random password
            }
        });
    }

    // ==================== Relationships ====================

    /**
     * The user account associated with this farmer (after activation)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The LGA this farmer belongs to
     */
    public function lga(): BelongsTo
    {
        return $this->belongsTo(LGA::class);
    }

    /**
     * The primary cooperative this farmer belongs to
     */
    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * All cooperatives this farmer is a member of (many-to-many)
     */
    public function cooperatives(): BelongsToMany
    {
        return $this->belongsToMany(Cooperative::class, 'cooperative_farmer')
            ->withPivot([
                'membership_number',
                'joined_date',
                'exit_date',
                'membership_status',
                'position',
                'notes'
            ])
            ->withTimestamps();
    }

    /**
     * The enrollment officer who registered this farmer
     */
    public function enrolledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    /**
     * The LGA admin who approved this farmer
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * All farm lands owned by this farmer
     */
    public function farmLands(): HasMany
    {
        return $this->hasMany(FarmLand::class);
    }

    // ==================== Scopes ====================

    /**
     * Scope for farmers in a specific LGA
     */
    public function scopeForLGA($query, $lgaId)
    {
        return $query->where('lga_id', $lgaId);
    }

    /**
     * Scope for farmers enrolled by a specific officer
     */
    public function scopeEnrolledBy($query, $userId)
    {
        return $query->where('enrolled_by', $userId);
    }

    /**
     * Scope for pending review farmers
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_lga_review');
    }

    /**
     * Scope for active farmers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for farmers needing activation
     */
    public function scopePendingActivation($query)
    {
        return $query->where('status', 'pending_activation');
    }

    // ==================== Helper Methods ====================

    /**
     * Get the farmer's age
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age ?? 0;
    }

    /**
     * Get full location string
     */
    public function getFullLocationAttribute(): string
    {
        return "{$this->ward}, {$this->lga->name}";
    }

    /**
     * Check if farmer has completed first login
     */
    public function hasChangedPassword(): bool
    {
        return $this->password_changed;
    }

    /**
     * Check if farmer can be activated
     */
    public function canBeActivated(): bool
    {
        return $this->status === 'pending_activation' && !empty($this->initial_password);
    }

    /**
     * Check if farmer is awaiting LGA approval
     */
    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_lga_review';
    }

    /**
     * Approve farmer enrollment
     */
    public function approve(User $admin): bool
    {
        $this->status = 'pending_activation';
        $this->approved_by = $admin->id;
        $this->approved_at = now();
        return $this->save();
    }

    /**
     * Reject farmer enrollment
     */
    public function reject(User $admin, string $reason): bool
    {
        $this->status = 'rejected';
        $this->approved_by = $admin->id;
        $this->rejection_reason = $reason;
        return $this->save();
    }

    /**
     * Activate farmer account
     */
    public function activate(User $user): bool
    {
        $this->user_id = $user->id;
        $this->status = 'active';
        $this->activated_at = now();
        return $this->save();
    }

    /**
     * Clear initial password after first login
     */
    public function clearInitialPassword(): bool
    {
        $this->initial_password = null;
        $this->password_changed = true;
        return $this->save();
    }

    /**
     * Get total farm land size
     */
    public function getTotalFarmSizeAttribute(): float
    {
        return $this->farmLands()->sum('total_size_hectares') ?? 0;
    }

    /**
     * Get count of farms by type
     */
    public function getFarmCountByType(string $type): int
    {
        return $this->farmLands()->where('farm_type', $type)->count();
    }
}