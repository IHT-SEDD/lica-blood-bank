<?php

namespace App\Models;

use App\Enums\OrderBloodStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderBlood extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'po_number',
        'vendor_id',
        'total_quantity',
        'description',
        'status',
        'ordered_by_user_id',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'status' => OrderBloodStatus::class,
    ];

    protected static function booted()
    {
        static::creating(function ($orderBlood) {
            // Generate public id
            if (empty($orderBlood->public_id)) {
                $orderBlood->public_id = (string) Str::uuid();
            }
        });
    }

    // Relasi ke vendors
    public function vendors(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Relasi ke users
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by_user_id');
    }


    // Relation from order_bloods
    public function orderBloods()
    {
        return $this->hasMany(OrderBloodDetail::class, 'order_blood_id');
    }
}
