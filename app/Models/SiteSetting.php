<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['setting_key', 'setting_value', 'setting_type', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    // Methods
    public static function getValue($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return match ($setting->setting_type) {
            'boolean' => filter_var($setting->setting_value, FILTER_VALIDATE_BOOLEAN),
            'number' => (int) $setting->setting_value,
            'array', 'json' => json_decode($setting->setting_value, true),
            default => $setting->setting_value,
        };
    }

    public static function setValue($key, $value, $type = 'string', $isPublic = false)
    {
        $settingValue = match ($type) {
            'array', 'json' => json_encode($value),
            'boolean' => $value ? 'true' : 'false',
            default => (string) $value,
        };

        return self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $settingValue,
                'setting_type' => $type,
                'is_public' => $isPublic
            ]
        );
    }
}
