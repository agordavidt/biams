<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{

    protected $table = 'agencies';


    use HasFactory;
    protected $fillable = ['name', 'department_id'];

    /**
     * Get all users scoped to this Agency.
     */
    public function users()
    {
        return $this->morphMany(User::class, 'administrative');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}