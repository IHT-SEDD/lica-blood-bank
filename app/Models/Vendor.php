<?php

namespace App\Models;

use App\Traits\InvalidateSelectCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, InvalidateSelectCache;

    protected $fillable = [
        'public_id',
        'name',
        'address',
        'phone_number',
        'telephone_number',
        'pic_name',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($vendor) {
            if (empty($vendor->public_id)) {
                $vendor->public_id = (string) Str::uuid();
            }
        });
    }

    // Buat peraturan validasi untuk data model
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'name' => 'string|required',
                'telephone_number' => 'nullable|min:8|unique:vendors,telephone_number',
                'phone_number' => 'nullable|min:8|unique:vendors,telephone_number',
            ],
            'update' => [
                'name' => 'sometimes|required|string',
                'telephone_number' => "nullable|min:8|unique:vendors,telephone_number,$id",
                'phone_number' => "nullable|min:8|unique:vendors,telephone_number,$id",
            ]
        };
    }
}
