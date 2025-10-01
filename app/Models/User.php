<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 
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
    
    // Add other scopes/methods (e.g., isOnboarded()) as needed...
}