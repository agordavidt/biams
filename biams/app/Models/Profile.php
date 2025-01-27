<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'dob', 
        'gender', 
        'nin',
        'education', 
        'household_size', 
        'dependents', 
        'income_level', 
        'lga', 
        'address',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}