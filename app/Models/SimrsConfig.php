<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SimrsConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'key',
        'label',
        'value',
        'group',
        'description',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    public static function getValue(string $key): string
    {
        $config = static::where('key', $key)->where('is_active', true)->first();

        if (!$config) {
            throw new \RuntimeException("SIMRS config key '{$key}' not found or inactive.");
        }

        return $config->value;
    }
}
