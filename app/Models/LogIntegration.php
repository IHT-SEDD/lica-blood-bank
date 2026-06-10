<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LogIntegration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'order_number',
        'endpoint',
        'payload',
        'message',
        'status',
        'type',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];


    protected static function booted()
    {
        static::creating(function ($orderBlood) {
            // Generate public id
            if (empty($orderBlood->public_id)) {
                $orderBlood->public_id = (string) Str::uuid();
            }
        });
    }
}
