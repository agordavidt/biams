<?php


// namespace App\Models\Farmers;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriculturalPractice extends Model
{
    use HasFactory;
    protected $primaryKey = 'practice_id';
    protected $fillable = ['practice_name', 'description'];
}