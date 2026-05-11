<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrderLogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'po_number',
        'vendor_name',
        'order_data',
        'order_blood_data',
        'payload',
        'created_by_user_name',
        'status',
        'description',
        'timestamp',
        'deleted_at',
        'po_file_path',
        'po_file_name',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'order_data' => 'array',
        'order_blood_data' => 'array',
        'payload' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($orderLogActivity) {
            // Generate public id
            if (empty($orderLogActivity->public_id)) {
                $orderLogActivity->public_id = (string) Str::uuid();
            }
        });
    }
}
