<?php

namespace App\Models;

use App\Enums\IncomingBloodStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IncomingBlood extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'order_blood_id',
        'po_number',
        'batch_number',
        'status',
        'received_by_user_id',
        'received_at',
        'stock_ready_at',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'status' => IncomingBloodStatus::class,
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

    // Relasi ke order_bloods
    public function orderBloods(): BelongsTo
    {
        return $this->belongsTo(OrderBlood::class, 'order_blood_id');
    }

    // Relasi ke users
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    // Relasi dari blood_stocks
    public function bloodStocks(): HasMany
    {
        return $this->hasMany(BloodStock::class, 'incoming_blood_id');
    }
}
