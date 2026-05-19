<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodTransfusionDetailTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'bt_detail_id',
        'test_id',
        'package_id',
        'type',
        'result_status',
        'result',
        'result_by_user_id',
        'verified_at',
        'verified_by_user_id',
        'validated_by_user_id',
        'validated_at'
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

    public function bloodTransfusionDetail()
    {
        return $this->belongsTo(BloodTransfusionDetail::class, 'bt_detail_id');
    }

    public function test()
    {
        return $this->belongsTo(\App\Models\Test::class, 'test_id');
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by_user_id');
    }

    public function validatedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'validated_by_user_id');
    }
}
