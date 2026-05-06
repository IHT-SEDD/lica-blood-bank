<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PackageTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];
    protected static function booted()
    {
        static::creating(function ($package_test) {
            if (empty($package_test->public_id)) {
                $package_test->public_id = (string) Str::uuid();
            }
        });
    }
}
