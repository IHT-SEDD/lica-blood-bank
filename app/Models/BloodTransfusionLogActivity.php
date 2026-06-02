<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodTransfusionLogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'blood_transfusion_public_id',
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
        static::creating(function ($bloodTransfusionLogAcitivity) {
            // Generate public id
            if (empty($bloodTransfusionLogAcitivity->public_id)) {
                $bloodTransfusionLogAcitivity->public_id = (string) Str::uuid();
            }
        });
    }
}
