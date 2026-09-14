<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BloodReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'blood_stock_id',
        'blood_transfusion_detail_id',
        'returned_from_status',
        'reason_return',
        'return_by_user_id',
        'return_by_user_name',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($bloodReturn) {
            if (empty($bloodReturn->public_id)) {
                $bloodReturn->public_id = (string) Str::uuid();
            }
        });
    }

    public function bloodStock(): BelongsTo
    {
        return $this->belongsTo(BloodStock::class, 'blood_stock_id');
    }

    public function bloodTransfusionDetail(): BelongsTo
    {
        return $this->belongsTo(BloodTransfusionDetail::class, 'blood_transfusion_detail_id');
    }

    public function returnByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'return_by_user_id');
    }
}
