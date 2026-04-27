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
        'blood_group',
        'blood_component',
        'blood_volume',
        'rhesus',
        'is_hiv',
        'is_hbsag',
        'is_hcv',
        'is_syphilis',
        'quantity',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'blood_group' => BloodGroup::class,
    ];

    protected static function booted()
    {
        static::creating(function ($orderBloodDetail) {
            if (empty($orderBloodDetail->public_id)) {
                $orderBloodDetail->public_id = (string) Str::uuid();
            }
        });
    }

    // Buat peraturan validasi untuk data model
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'order_blood_id' => 'required|exists:order_bloods,id',
                'blood_group' => ['required', new Enum(BloodGroup::class)],
                'blood_volume' => 'required|integer|min:1',
                'rhesus' => 'required|in:+,-',
            ],
            'update' => [
                'order_blood_id' => 'sometimes|required|exists:order_bloods,id',
                'blood_group' => ['sometimes', 'required', new Enum(BloodGroup::class)],
                'blood_volume' => 'sometimes|required|integer|min:1',
                'rhesus' => 'sometimes|required|in:+,-',
            ]
        };
    }

    // Relation to order_bloods
    public function orderBloods(): BelongsTo
    {
        return $this->belongsTo(OrderBlood::class, 'order_blood_id');
    }
}
