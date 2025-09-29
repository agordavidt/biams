<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'businessName',
        'reference',
        'transAmount',
        'transFee',
        'transTotal',
        'transDate',
        'settlementAmount',
        'status',
        'statusMessage',
        'customerId',
        'resourceId',
        'resourceOwnerId',
        'channelId',
        'currencyCode',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transDate' => 'datetime',
        'transAmount' => 'decimal:2',
        'transFee' => 'decimal:2',
        'transTotal' => 'decimal:2',
        'settlementAmount' => 'decimal:2',
    ];

    /**
     * Get the customer (user) that owns the payment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customerId');
    }

    /**
     * Get the resource owner (user) that owns the payment.
     */
    public function resourceOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resourceOwnerId');
    }

    /**
     * Get the resource associated with the payment.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resourceId');
    }
}
