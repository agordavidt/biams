<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Carbon\Carbon;


class MarketplaceInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'buyer_name',
        'buyer_phone',
        'buyer_email',
        'message',
        'buyer_ip',
        'status',
        'contacted_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
    ];

    // Relationships
    public function listing()
    {
        return $this->belongsTo(MarketplaceListing::class, 'listing_id');
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeForListing($query, $listingId)
    {
        return $query->where('listing_id', $listingId);
    }

    // Methods
    public function markAsContacted()
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
        ]);
    }

    public function markAsConverted()
    {
        $this->update(['status' => 'converted']);
    }
}