<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PackageTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'is_active',
        'package_id',
        'test_id'
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

    // Relation to order_bloods
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    // Relation to blood_packs
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
}
