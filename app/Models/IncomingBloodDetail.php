<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IncomingBloodDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'incoming_blood_id',
        'bag_number',
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
        'is_ready',
        'ready_at',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($incomingBloodDetail) {
            // Generate public id
            if (empty($incomingBloodDetail->public_id)) {
                $incomingBloodDetail->public_id = (string) Str::uuid();
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
