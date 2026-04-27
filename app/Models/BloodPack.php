<?php

namespace App\Models;

use App\Enums\BloodGroup;
use App\Enums\BloodComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class BloodPack extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'incoming_blood_id',
        'bag_number',
        'blood_group',
        'rhesus',
        'blood_component',
        'blood_volume',
        'aftap_date',
        'process_date',
        'expiry_date',
        'is_hiv',
        'is_hbsag',
        'is_hcv',
        'is_syphilis',
        'blood_status',
        'is_expired',
        'used_at',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'blood_group' => BloodGroup::class,
        'blood_component' => BloodComponent::class,
    ];

    protected static function booted()
    {
        static::creating(function ($bloodPack) {
            // Generate public id
            if (empty($bloodPack->public_id)) {
                $bloodPack->public_id = (string) Str::uuid();
            }
        });
    }

    // Buat peraturan validasi untuk data model
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'incoming_blood_id' => 'required|exists:incoming_bloods,id',
                'bag_number' => 'required|string|unique:blood_packs,bag_number',
                'blood_group' => ['required', new Enum(BloodGroup::class)],
                'rhesus' => 'required|in:+,-',
                'blood_component' => ['required', new Enum(BloodComponent::class)],
            ],
            'update' => [
                'incoming_blood_id' => 'sometimes|required|exists:incoming_bloods,id',
                'bag_number' => 'sometimes|required|string|unique:blood_packs,bag_number',
                'blood_group' => ['sometimes', 'required', new Enum(BloodGroup::class)],
                'rhesus' => 'sometimes|required|in:+,-',
                'blood_component' => ['sometimes', 'required', new Enum(BloodComponent::class)],
            ]
        };
    }

    // Relation to incoming_bloods
    public function incomingBloods(): BelongsTo
    {
        return $this->belongsTo(IncomingBlood::class, 'incoming_blood_id');
    }
}
