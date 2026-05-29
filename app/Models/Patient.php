<?php

namespace App\Models;

use App\Traits\InvalidateSelectCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory, SoftDeletes, InvalidateSelectCache;

    protected $fillable = [
        'public_id',
        'name',
        'blood_group',
        'blood_rhesus',
        'medrec',
        'gender',
        'birthdate',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birthdate' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($patient) {
            if (empty($patient->public_id)) {
                $patient->public_id = (string) Str::uuid();
            }
        });

         static::created(function ($patient) {
            if (empty($patient->medrec)) {
                $patient->updateQuietly([
                    'medrec' => date('y') . str_pad($patient->id, 5, '0', STR_PAD_LEFT)
                ]);
            }
        });
    }

    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'name' => 'required|string|max:255',
                // 'medrec' => 'required|string|max:255|unique:patients,medrec',
                'gender' => 'required|in:F,M',
                'birthdate' => 'required|date',
                'phone' => 'nullable|string|max:30',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
                'is_active' => 'sometimes|boolean',
            ],
            'update' => [
                'name' => 'sometimes|required|string|max:255',
                'medrec' => "sometimes|required|string|max:255|unique:patients,medrec,$id",
                'gender' => 'sometimes|required|in:F,M',
                'birthdate' => 'sometimes|required|date',
                'phone' => 'nullable|string|max:30',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
                'is_active' => 'sometimes|boolean',
            ],
        };
    }
}
