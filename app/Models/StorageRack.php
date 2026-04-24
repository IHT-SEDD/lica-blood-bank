<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StorageRack extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'name',
        'storage_id',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    // Generate a public_id if not provided
    protected static function booted()
    {
        static::creating(function ($rack) {
            if (empty($rack->public_id)) {
                $rack->public_id = (string) Str::uuid();
            }
        });
    }

    // Relation to storages
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }
}
