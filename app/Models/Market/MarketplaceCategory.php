<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Carbon\Carbon;


class MarketplaceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function listings()
    {
        return $this->hasMany(MarketplaceListing::class, 'category_id');
    }

    public function activeListings()
    {
        return $this->listings()
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderBy('display_order');
    }
}