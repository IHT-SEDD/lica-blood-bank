<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StorageRackBlood extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'storage_rack_id',
        'blood_stock_id',
    ];

    protected $hidden = [
        'id',
    ];

    // Generate a public_id if not provided
    protected static function booted()
    {
        static::creating(function ($storageRackBlood) {
            if (empty($storageRackBlood->public_id)) {
                $storageRackBlood->public_id = (string) Str::uuid();
            }
        });
    }

    // Relation to storage_racks
    public function storageRacks(): BelongsTo
    {
        return $this->belongsTo(StorageRack::class, 'storage_rack_id');
    }

    // Relation to blood_stocks
    public function bloodStock(): BelongsTo
    {
        return $this->belongsTo(BloodStock::class, 'blood_stock_id');
    }
}
