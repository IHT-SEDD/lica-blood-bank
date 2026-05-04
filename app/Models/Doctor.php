<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'is_active',
        'name'
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
