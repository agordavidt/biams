<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cooperative extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_number',
        'name',
        'contact_person',
        'phone',
        'email',
        'total_member_count',
        'total_land_size',
        'primary_activities',
        'lga_id',
        'registered_by',
    ];

    protected $casts = [
        'primary_activities' => 'array',
        'total_land_size' => 'decimal:2',
    ];

    // ==================== Relationships ====================

    /**
     * The primary LGA this cooperative is based in.
     */
    public function lga(): BelongsTo
    {
        return $this->belongsTo(LGA::class);
    }

    /**
     * The farmers who have this cooperative set as their primary cooperative (one-to-many).
     */
    public function primaryFarmers(): HasMany
    {
        return $this->hasMany(Farmer::class, 'cooperative_id');
    }

    /**
     * All farmers who are members of this cooperative (many-to-many).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Farmer::class, 'cooperative_farmer')
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
}