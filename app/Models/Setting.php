<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $cached = Cache::get("setting.{$key}");
        if ($cached !== null) {
            return $cached;
        }
        $setting = static::where('key', $key)->first();
        $value = $setting ? $setting->value : $default;
        Cache::put("setting.{$key}", $value, 3600);

        return $value;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
    }
}
