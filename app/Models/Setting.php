<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function imageUrl($key)
    {
        $path = static::get($key);
        if ($path && !str_starts_with($path, 'http')) {
            return asset('storage/' . $path);
        }
        return $path;
    }
}