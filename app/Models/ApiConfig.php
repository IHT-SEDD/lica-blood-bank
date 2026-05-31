<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'name',
        'token',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($apiConfig) {
            // Generate public id
            if (empty($apiConfig->public_id)) {
                $apiConfig->public_id = (string) Str::uuid();
            }
        });
    }
}
