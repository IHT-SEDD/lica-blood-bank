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
}
