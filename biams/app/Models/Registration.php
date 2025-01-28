<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    protected $primaryKey = 'registration_id';
    protected $fillable = ['user_id', 'practice_id', 'status', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function practice()
    {
        return $this->belongsTo(AgriculturalPractice::class, 'practice_id');
    }
}