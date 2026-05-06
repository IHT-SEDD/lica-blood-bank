<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'is_active',
        'name',
        'type',
        'class',
        'general_code'
    ];

    protected $hidden = [
        'id',
    ];
    protected static function booted()
    {
        static::creating(function ($room) {
            if (empty($room->public_id)) {
                $room->public_id = (string) Str::uuid();
            }
        });
    }
}
