<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Storage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'name',
        'model',
        'serial_number',
        'manufacturer',
        'rack_capacity',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    // Generate a public_id if not provided
    protected static function booted()
    {
        static::creating(function ($storage) {
            if (empty($storage->public_id)) {
                $storage->public_id = (string) Str::uuid();
            }
        });
    }

    // Relation from storage_racks
    public function storageRacks()
    {
        return $this->hasMany(StorageRack::class, 'storage_id');
    }
}
