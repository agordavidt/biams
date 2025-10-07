<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $fillable = [
        'farmer_id',
        'assigned_admin_id',
        'lga_id',
        'subject',
        'status',
        'priority',
        'last_message_at',
        'assigned_at',
        'resolved_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    // public function farmer(): BelongsTo
    // {
    //     return $this->belongsTo(Farmer::class);
    // }

        /**
     * CRITICAL: Relationship to Farmer model
     */
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function lga(): BelongsTo
    {
        return $this->belongsTo(LGA::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // Scopes for Role-Based Filtering
    public function scopeForLGA($query, $lgaId)
    {
        return $query->where('lga_id', $lgaId);
    }

    public function scopeForFarmer($query, $farmerId)
    {
        return $query->where('farmer_id', $farmerId);
    }

    public function scopeAssignedTo($query, $adminId)
    {
        return $query->where('assigned_admin_id', $adminId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'pending_farmer']);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    // Helper Methods
    public function markAsAssigned(User $admin): void
    {
        $this->update([
            'assigned_admin_id' => $admin->id,
            'status' => 'in_progress',
            'assigned_at' => now(),
        ]);
    }

    public function markAsResolved(): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function getUnreadCountForUser(User $user): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
    }
}

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'sender_id',
        'body',
        'attachments',
        'sender_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Helper Methods
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}