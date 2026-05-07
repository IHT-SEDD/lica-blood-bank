<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodTransfusionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'blood_transfusion_id',
        'blood_stock_id',
        'transfusion_text',
        'transfusion_at',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = (string) Str::uuid();
            }
        });
    }

    public function bloodTransfusion(): BelongsTo
    {
        return $this->belongsTo(BloodTransfusion::class, 'blood_transfusion_id');
    }

    public function bloodStock(): BelongsTo
    {
        return $this->belongsTo(BloodStock::class, 'blood_stock_id');
    }

    public function bloodTransfusionDetailTest()
    {
        return $this->hasOne(BloodTransfusionDetailTest::class, 'bt_detail_id');
    }
}
