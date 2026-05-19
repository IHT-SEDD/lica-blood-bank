<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IncomingBloodLogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'incoming_blood_public_id',
        'po_number',
        'batch_number',
        'incoming_data',
        'blood_data',
        'status',
        'created_by_user_name',
        'description',
        'deleted_at',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'incoming_data' => 'array',
        'blood_data' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($incomingBloodLogActivity) {
            // Generate public id
            if (empty($incomingBloodLogActivity->public_id)) {
                $incomingBloodLogActivity->public_id = (string) Str::uuid();
            }
        });
    }
}
