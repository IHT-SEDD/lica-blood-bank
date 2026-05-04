<?php

namespace App\Models;

use App\Enums\BloodComponent;
use App\Enums\BloodGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'incoming_blood_id',
        'bag_number',
        'bag_number_lica',
        'blood_pack_id',
        'blood_volume',
        'aftap_date',
        'process_date',
        'expiry_date',
        'is_hiv',
        'is_hbsag',
        'is_hcv',
        'is_syphilis',
        'is_expired',
        'used_at',
        'blood_status',
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
        static::creating(function ($bloodStock) {
            // Generate public id
            if (empty($bloodStock->public_id)) {
                $bloodStock->public_id = (string) Str::uuid();
            }
        });
    }

    // Relation to incoming_bloods
    public function incomingBloods(): BelongsTo
    {
        return $this->belongsTo(IncomingBlood::class, 'incoming_blood_id');
    }

    // Relation to blood_packs
    public function bloodPacks(): BelongsTo
    {
        return $this->belongsTo(BloodPack::class, 'blood_pack_id');
    }
}
