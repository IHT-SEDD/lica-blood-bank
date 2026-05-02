<?php

namespace App\Models;

use App\Enums\BloodGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class OrderBloodDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'order_blood_id',
        'blood_pack_id',
        'note',
        'is_hiv',
        'is_hbsag',
        'is_hcv',
        'is_syphilis',
        'quantity',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($orderBloodDetail) {
            if (empty($orderBloodDetail->public_id)) {
                $orderBloodDetail->public_id = (string) Str::uuid();
            }
        });
    }

    // Relation to order_bloods
    public function orderBloods(): BelongsTo
    {
        return $this->belongsTo(OrderBlood::class, 'order_blood_id');
    }

    // Relation to blood_packs
    public function bloodPacks(): BelongsTo
    {
        return $this->belongsTo(BloodPack::class, 'blood_pack_id');
    }
}
