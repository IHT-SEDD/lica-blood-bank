<?php

namespace App\Models;

use App\Enums\OrderBloodStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderBlood extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'po_number',
        'vendor_id',
        'total_quantity',
        'status',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'status' => OrderBloodStatus::class,
    ];

    protected static function booted()
    {
        static::creating(function ($orderBlood) {
            // Generate public id
            if (empty($orderBlood->public_id)) {
                $orderBlood->public_id = (string) Str::uuid();
            }

            // Generate po number
            if (empty($orderBlood->po_number)) {

                $year = now()->format('Y');

                DB::transaction(function () use ($orderBlood, $year) {
                    $last = self::where('po_number', 'like', "P{$year}OB%")
                        ->lockForUpdate()
                        ->orderByDesc('po_number')
                        ->first();

                    $nextNumber = $last
                        ? ((int) substr($last->po_number, -6) + 1)
                        : 1;

                    $orderBlood->po_number =
                        'P' . $year . 'OB' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                });
            }
        });
    }

    // Buat peraturan validasi untuk data model
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'total_quantity' => 'required',
                'po_number' => 'required|unique:order_bloods,po_number',
                'vendor_id' => 'required|exists:vendors,id',
            ],
            'update' => [
                'total_quantity' => 'sometimes|required',
                'po_number' => "sometimes|required|unique:order_bloods,po_number,$id",
                'vendor_id' => 'sometimes|required|exists:vendors,id',
            ]
        };
    }

    // Relasi ke vendors
    public function vendors(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }


    // Relation from order_bloods
    public function orderBloods()
    {
        return $this->hasMany(OrderBloodDetail::class, 'order_blood_id');
    }
}
