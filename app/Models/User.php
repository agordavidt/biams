<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 
use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceSubscription;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; //  Use Spatie Trait

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'status',
        'administrative_id',
        'administrative_type',
        // Add all other columns here
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the owning administrative unit (LGA, Department, or Agency).
     */
    public function administrativeUnit()
    {
        // Define the polymorphic relationship for scoping the user
        return $this->morphTo('administrative');
    }
    
   public function scopeForAdministrativeUnit($query, $type, $id)
    {
        return $query->where('administrative_type', $type)
                    ->where('administrative_id', $id);
    }

    public function scopeForLGA($query, $lgaId)
    {
        return $query->forAdministrativeUnit(LGA::class, $lgaId);
    }

    // ====================Chat  Relationships ====================
    // Add a reverse relationship to the Farmer profile.
    public function farmerProfile()
    {
        // A User can only have one Farmer profile associated with it
        return $this->hasOne(Farmer::class, 'user_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'assigned_admin_id');
    }

    // ==================== Marketplace Relationships ====================
    
    /**
     * Get all marketplace listings created by this user.
     */
    public function marketplaceListings()
    {
        return $this->hasMany(MarketplaceListing::class, 'user_id');
    }

    /**
     * Get active marketplace listings.
     */
    public function activeMarketplaceListings()
    {
        return $this->marketplaceListings()
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get all marketplace subscriptions.
     */
    public function marketplaceSubscriptions()
    {
        return $this->hasMany(MarketplaceSubscription::class, 'user_id');
    }

    /**
     * Get the current active marketplace subscription.
     */
    public function activeMarketplaceSubscription()
    {
        return $this->hasOne(MarketplaceSubscription::class, 'user_id')
            ->where('status', 'paid')
            ->where('end_date', '>', now())
            ->latest('end_date');
    }

    /**
     * Check if user has an active marketplace subscription.
     */
    public function hasActiveMarketplaceSubscription(): bool
    {
        return $this->marketplaceSubscriptions()
            ->where('status', 'paid')
            ->where('end_date', '>', now())
            ->exists();
    }

    /**
     * Get marketplace subscription expiry date.
     */
    public function getMarketplaceSubscriptionExpiryAttribute()
    {
        $subscription = $this->activeMarketplaceSubscription;
        return $subscription?->end_date;
    }

    /**
     * Check if marketplace subscription is expiring soon (within 30 days).
     */
    public function isMarketplaceSubscriptionExpiringSoon(): bool
    {
        $subscription = $this->activeMarketplaceSubscription;
        
        if (!$subscription) {
            return false;
        }

        return $subscription->end_date->diffInDays(now()) <= 30;
    }

    /**
     * Get total marketplace inquiries received.
     */
    public function getTotalMarketplaceInquiriesAttribute()
    {
        return $this->marketplaceListings()->sum('inquiry_count');
    }

    /**
     * Get total marketplace views received.
     */
    public function getTotalMarketplaceViewsAttribute()
    {
        return $this->marketplaceListings()->sum('view_count');
    }


    
}