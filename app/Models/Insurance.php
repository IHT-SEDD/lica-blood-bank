<?php

namespace App\Models;

use App\Traits\InvalidateSelectCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Insurance extends Model
{
    use HasFactory, SoftDeletes, InvalidateSelectCache;

    protected $fillable = [
        'public_id',
        'is_active',
        'name',
        'general_code'

    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($insurance) {
            if (empty($insurance->public_id)) {
                $insurance->public_id = (string) Str::uuid();
            }
        });
    }
}
