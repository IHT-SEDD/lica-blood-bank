<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'is_active',
        'name',
        'blood_component',
        'general_code'
    ];

    protected $hidden = [
        'id',
    ];
    protected static function booted()
    {
        static::creating(function ($package) {
            if (empty($package->public_id)) {
                $package->public_id = (string) Str::uuid();
            }
        });
    }
    public function package_tests()
    {
        return $this->hasMany(PackageTest::class, 'package_id');
    }
}
