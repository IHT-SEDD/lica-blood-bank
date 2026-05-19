<?php

namespace App\Models;

use App\Enums\BloodDestroyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodDestroy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'blood_stock_id',
        'reason',
        'status',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'status' => BloodDestroyStatus::class,
    ];

    protected static function booted()
    {
        static::creating(function ($bloodDestroy) {
            // Generate public id
            if (empty($bloodDestroy->public_id)) {
                $bloodDestroy->public_id = (string) Str::uuid();
            }
        });
    }

    // Relation to blood_stocks
    public function bloodStocks(): BelongsTo
    {
        return $this->belongsTo(BloodStock::class, 'blood_stock_id');
    }
}
