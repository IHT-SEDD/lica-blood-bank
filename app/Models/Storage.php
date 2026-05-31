<?php

namespace App\Models;

use App\Traits\InvalidateSelectCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Storage extends Model
{
    use SoftDeletes, InvalidateSelectCache;

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

    // Buat peraturan validasi untuk add data model user
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'name' => 'string|required',
                'rack_capacity' => 'required',
            ],
            'update' => [
                'name' => 'sometimes|string|required',
                'rack_capacity' => 'sometimes|required',
            ]
        };
    }

    // Relation from storage_racks
    public function storageRacks()
    {
        return $this->hasMany(StorageRack::class, 'storage_id');
    }
}
