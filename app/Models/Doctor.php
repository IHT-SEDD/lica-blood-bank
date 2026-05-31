<?php

namespace App\Models;

use App\Traits\InvalidateSelectCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Doctor extends Model
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
        static::creating(function ($doctor) {
            if (empty($doctor->public_id)) {
                $doctor->public_id = (string) Str::uuid();
            }
        });
    }
}
