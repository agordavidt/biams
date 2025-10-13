<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LGA extends Model
{
    protected $table = 'lgas';

    
    use HasFactory;
    protected $fillable = ['name', 'code'];

    /**
     * Get all users scoped to this LGA.
     */
    public function users()
    {
        return $this->morphMany(User::class, 'administrative');
    }

     /**
     * Get all farmers in this LGA.
     */
    public function farmers()
    {
        return $this->hasMany(Farmer::class, 'lga_id');
    }
}


