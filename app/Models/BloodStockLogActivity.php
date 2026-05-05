<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodStockLogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'blood_stock_public_id',
        'payload',
        'status',
        'description',
        'created_by_user_name',
        'timestamp',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($bloodStockLogActivity) {
            // Generate public id
            if (empty($bloodStockLogActivity->public_id)) {
                $bloodStockLogActivity->public_id = (string) Str::uuid();
            }
        });
    }
}
