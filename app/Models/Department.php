<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    use HasFactory;
    protected $fillable = ['name', 'abbreviation'];

    /**
     * Get all users scoped to this Department.
     */
    public function users()
    {
        return $this->morphMany(User::class, 'administrative');
    }

    public function agencies()
    {
        return $this->hasMany(Agency::class);
    }
}
