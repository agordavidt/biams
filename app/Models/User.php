<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmail; 

use App\Models\Farmers\AbattoirOperator;
use App\Models\Farmers\CropFarmer;
use App\Models\Farmers\AnimalFarmer;
use App\Models\Farmers\Processor;
use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceCategory;
use App\Models\Market\MarketplaceMessage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'rejection_reason',
        'rejected_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'rejected_at' => 'datetime',
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        try {
            $this->notify(new CustomVerifyEmail);
        } catch (\Exception $e) {
            \Log::error('Notification failed: ' . $e->getMessage(), [
                'user_id' => $this->id,
                'notification' => CustomVerifyEmail::class,
            ]);
        }
    }

    /**
     * Scope a query to only include users with role 'user'.
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope a query to only include pending users.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include onboarded users.
     */
    public function scopeOnboarded($query)
    {
        return $query->where('status', 'onboarded');
    }

    /**
     * Scope a query to only include rejected users.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if user is onboarded.
     */
    public function isOnboarded()
    {
        return $this->status === 'onboarded';
    }

    /**
     * Check if user is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a regular user.
     */
    public function isRegularUser()
    {
        return $this->role === 'user';
    }

    // Relationships
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function cropFarmers()
    {
        return $this->hasMany(CropFarmer::class);
    }

    public function animalFarmers()
    {
        return $this->hasMany(AnimalFarmer::class);
    }

    public function abattoirOperators()
    {
        return $this->hasMany(AbattoirOperator::class);
    }

    public function processors()
    {
        return $this->hasMany(Processor::class);
    }

    // Marketplace Relationships
    public function marketplaceListings()
    {
        return $this->hasMany(MarketplaceListing::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(MarketplaceMessage::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(MarketplaceMessage::class, 'receiver_id');
    }
}