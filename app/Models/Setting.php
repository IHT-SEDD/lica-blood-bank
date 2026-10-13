<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'key',
        'value',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($setting) {
            if (empty($setting->public_id)) {
                $setting->public_id = (string) Str::uuid();
            }
        });
    }

    // Ambil setting bertipe boolean (di-cache agar middleware tidak query DB tiap request)
    public static function getBool(string $key, bool $default = false): bool
    {
        $value = Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? ($default ? '1' : '0');
        });

        return $value === '1';
    }

    // Simpan setting bertipe boolean lalu hapus cache-nya
    public static function setBool(string $key, bool $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value ? '1' : '0']);

        Cache::forget("setting:{$key}");
    }
}
