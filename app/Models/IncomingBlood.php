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
        'registered_by_user_id',
        'stock_ready_at',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'status' => IncomingBloodStatus::class,
    ];

    protected $appends = [
        'incoming_blood_groups',
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

    public function getIncomingBloodGroupsAttribute(): string
    {
        if (!$this->relationLoaded('incomingBloodDetails')) {
            return '';
        }

        return $this->incomingBloodDetails
            ->map(function ($detail) {

                if (!$detail->bloodPacks) {
                    return null;
                }

                return collect([
                    $detail->bloodPacks->blood_group->value .
                        $detail->bloodPacks->blood_rhesus,

                    $detail->bloodPacks->blood_component->value,
                ])->filter()->join(' ');
            })
            ->filter()
            ->unique()
            ->implode(', ');
    }

    // Relasi ke order_bloods
    public function orderBloods(): BelongsTo
    {
        return $this->belongsTo(OrderBlood::class, 'order_blood_id');
    }

    // User penerima
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    // User pendaftar
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    // Relasi dari incoming_blood_details
    public function incomingBloodDetails(): HasMany
    {
        return $this->hasMany(IncomingBloodDetail::class, 'incoming_blood_id');
    }
}
