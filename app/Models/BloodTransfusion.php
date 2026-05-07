<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BloodTransfusion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'patient_id',
        'doctor_id',
        'room_id',
        'insurance_id',
        'lab_number',
        'order_number',
        'relation_name',
        'relation_type',
        'blood_request_at',
        'diagnosis',
        'finish_at',
        'status',
        'blood_quantity',
        'checkin_by_user_id',
        'finish_by_user_id',
        'deleted_by_user_id',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function details()
    {
        return $this->hasMany(BloodTransfusionDetail::class, 'blood_transfusion_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
