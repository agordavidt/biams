<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Carbon\Carbon;


class MarketplaceListingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'image_path',
        'thumbnail_path',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function listing()
    {
        return $this->belongsTo(MarketplaceListing::class, 'listing_id');
    }

    // Methods
    public function makePrimary()
    {
        // Remove primary flag from other images
        $this->listing->images()->update(['is_primary' => false]);
        
        // Set this image as primary
        $this->update(['is_primary' => true]);
    }
}
