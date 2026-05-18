<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CrossmatchHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'blood_stock_id',
        'blood_transfusion_id',
        'patient_name',
        'result',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($crossmatchHistory) {
            // Generate public id
            if (empty($crossmatchHistory->public_id)) {
                $crossmatchHistory->public_id = (string) Str::uuid();
            }
        });
    }

    // Relation to blood_stocks
    public function bloodStocks(): BelongsTo
    {
        return $this->belongsTo(BloodStock::class, 'blood_stock_id');
    }

    // Relation to blood_transfusion
    public function bloodPacks(): BelongsTo
    {
        return $this->belongsTo(BloodTransfusion::class, 'blood_transfusion_id');
    }
}
