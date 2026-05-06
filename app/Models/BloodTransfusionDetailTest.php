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
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];
}
