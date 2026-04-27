<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IncomingBlood extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'po_number',
        'order_blood_id',
        'batch_number',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($incomingBlood) {
            // Generate public id
            if (empty($incomingBlood->public_id)) {
                $incomingBlood->public_id = (string) Str::uuid();
            }
        });
    }

    // Buat peraturan validasi untuk data model
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'total_quantity' => 'required',
                'po_number' => 'required|exists:order_bloods,po_number',
                'order_blood_id' => 'required|exists:order_bloods,id',
            ],
            'update' => [
                'total_quantity' => 'sometimes|required',
                'po_number' => "sometimes|required|exists:order_bloods,po_number",
                'order_blood_id' => 'sometimes|required|exists:order_bloods,id',
            ]
        };
    }

    // Relasi ke order_bloods
    public function orderBloods(): BelongsTo
    {
        return $this->belongsTo(OrderBlood::class, 'order_blood_id');
    }
}
