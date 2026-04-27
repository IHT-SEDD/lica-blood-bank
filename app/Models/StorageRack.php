<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

    // Buat peraturan validasi untuk add data model user
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'name' => 'string|required',
                'storage_id' => 'required|exists:storages,public_id',
            ],
            'update' => [
                'name' => 'sometimes|string|required',
                'storage_id' => 'sometimes|required|exists:storages,public_id',
            ]
        };
    }

    // Ambil ID dari tabel storages berdasarkan public_id
    public static function resolveStorageId($publicId)
    {
        return Storage::where('public_id', $publicId)->value('id');
    }

    // Hook sebelum submit data
    public static function beforeCreate(array &$data)
    {
        $storageId = self::resolveStorageId($data['storage_id']);

        if (!$storageId) {
            throw ValidationException::withMessages([
                'storage_id' => ['Storage not found']
            ]);
        }

        $data['storage_id'] = $storageId;

        self::checkCapacity($storageId);
    }

    // Hook sebelum perubahan data
    public static function beforeUpdate(array &$data, $record)
    {
        logger('BEFORE UPDATE HIT', $data);

        if (!array_key_exists('storage_id', $data)) {
            return;
        }

        $newStorageId = self::resolveStorageId($data['storage_id']);
        if (!$newStorageId) {
            throw ValidationException::withMessages([
                'storage_id' => ['Storage not found']
            ]);
        }
        $data['storage_id'] = $newStorageId;

        if ($newStorageId == $record->storage_id) {
            return;
        }

        self::checkCapacity($newStorageId);
    }

    // Pengecekan jumlah kapasitas storage un
    public static function checkCapacity($storageId)
    {
        $storage = Storage::where('id', $storageId)
            ->lockForUpdate()
            ->firstOrFail();

        $count = self::where('storage_id', $storageId)->count();

        if ($count >= $storage->rack_capacity) {
            throw ValidationException::withMessages([
                'storage_id' => ['Storage max capacity has been reached']
            ]);
        }
    }

    // Relation to storages
    public function storages(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }
}
