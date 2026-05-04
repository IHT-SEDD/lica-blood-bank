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
        'blood_group',
        'blood_rhesus',
        'blood_component',
        'warning_quantity',
        'danger_quantity',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'blood_group' => BloodGroup::class,
        'blood_component' => BloodComponent::class,
    ];

    public function getLabelAttribute()
    {
        return "{$this->blood_group->value}{$this->blood_rhesus} {$this->blood_component->value}";
    }

    protected static function booted()
    {
        static::creating(function ($bloodPack) {
            // Generate public id
            if (empty($bloodPack->public_id)) {
                $bloodPack->public_id = (string) Str::uuid();
            }
        });
    }

    // Relasi dari blood_stocks
    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class);
    }
}
