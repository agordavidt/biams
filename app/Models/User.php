<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmail; // Import the custom verification notification

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
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
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


