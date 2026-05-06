<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Test extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'is_active',
        'name',
        'blood_group',
        'blood_rhesus',
        'blood_component',
        'general_code'
    ];

    protected $hidden = [
        'id',
    ];
}
