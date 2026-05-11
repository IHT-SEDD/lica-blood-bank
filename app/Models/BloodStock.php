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
        'bag_number',
        'bag_number_lica',
        'incoming_blood_detail_id',
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
        'blood_status',
        'add_new_note',
        'note',
        'used_at',
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

    // Relation to incoming_blood_details
    public function incomingBloodDetails(): BelongsTo
    {
        return $this->belongsTo(IncomingBloodDetail::class, 'incoming_blood_detail_id');
    }

    // Relation to blood_packs
    public function bloodPacks(): BelongsTo
    {
        return $this->belongsTo(BloodPack::class, 'blood_pack_id');
    }
}
