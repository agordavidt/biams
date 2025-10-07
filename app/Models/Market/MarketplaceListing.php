<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Carbon\Carbon;


class MarketplaceListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'unit',
        'quantity',
        'location',
        'contact',
        'status',
        'rejection_reason',
        'expires_at',
        'approved_at',
        'approved_by',
        'view_count',
        'inquiry_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'price' => 'decimal:2',
        'view_count' => 'integer',
        'inquiry_count' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(MarketplaceListingImage::class, 'listing_id')
            ->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(MarketplaceListingImage::class, 'listing_id')
            ->where('is_primary', true);
    }

    public function inquiries()
    {
        return $this->hasMany(MarketplaceInquiry::class, 'listing_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getPrimaryImagePathAttribute()
    {
        $primaryImage = $this->images->where('is_primary', true)->first() 
            ?? $this->images->first();
        
        return $primaryImage ? $primaryImage->image_path : 'marketplace/placeholder.jpg';
    }

    public function getFormattedPriceAttribute()
    {
        return '₦' . number_format($this->price, 2);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && !$this->is_expired;
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }
        
        $diff = now()->diffInDays($this->expires_at, false);
        return $diff > 0 ? $diff : 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%');
        });
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementInquiryCount()
    {
        $this->increment('inquiry_count');
    }

    public function approve(User $admin)
    {
        $this->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);
    }

    public function reject($reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function markAsExpired()
    {
        if ($this->status === 'active' && $this->is_expired) {
            $this->update(['status' => 'expired']);
        }
    }
}
