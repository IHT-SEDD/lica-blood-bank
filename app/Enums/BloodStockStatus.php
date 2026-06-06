<?php

namespace App\Enums;

enum BloodStockStatus: string
{
    // ------ Alur Status Blood Stock
    // -- in_use ketika kantong darah sudah terpilih oleh pasien untuk crossmatch
    // -- taken_out ketika kantong darah sudah di release atau dikeluarkan
    // -- used ketika kantong darah sudah digunakan atau tidak dikeluarkan/tidak di release
    // -- hold ketika kantong darah sudah sedang menunggu approval untuk di release atau tidak. Berlaku untuk hasil crossmatch incompatible

    // --- Inventory
    case REGISTERED = 'registered'; // Untuk kantong darah yang sudah didaftarkan tapi belum ada digudang
    case AVAILABLE = 'available'; // Untuk kantong darah yang belum digunakan sama sekali
    case RETURNED = 'returned'; // Untuk kantong darah yang sudah dikembalikan
    case DESTROYED = 'destroyed'; // Untuk kantong darah yang sudah dimusnahkan
    case DELETED = 'deleted'; // Untuk kantong darah yang sudah dihapus
    case RESTORED = 'restored'; // Untuk kantong darah yang sudah dipulihkan dari penghapusan

        // --- Blood Transfusion
    case IN_USE = 'in_use'; // Untuk kantong darah yang sedang digunakan di blood transfusion
    case USED = 'used'; // Untuk kantong darah yang sudah pernah dipakai dan bisa dipakai pasien lain

    case HOLD = 'hold'; // Untuk kantong darah yang sudah diambil pasien

    case ALREADY_TAKEN = 'already_taken'; // Untuk kantong darah yang sudah diambil pasien
    case TAKEN_OUT = 'taken_out'; // Untuk kantong darah yang sudah dikeluarkan dari gudang
    case EXPIRED = 'expired'; // Untuk kantong darah yang sudah expired

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => 'Terdaftar',
            self::AVAILABLE => 'Tersedia',
            self::RETURNED => 'Dikembalikan',
            self::DESTROYED => 'Dimusnahkan',
            self::DELETED => 'Dihapus',
            self::RESTORED => 'Dipulihkan',

            self::IN_USE => 'Sedang Digunakan',
            self::USED => 'Sudah Pernah Digunakan',

            self::HOLD => 'Sedang Di Tahan',

            self::ALREADY_TAKEN => 'Sudah Ada Pemilik',
            self::TAKEN_OUT => 'Dikeluarkan',
            self::EXPIRED => 'Expire',
        };
    }

    public static function toSelect(): array
    {
        return array_map(fn($item) => [
            'id' => $item->value,
            'text' => $item->label(),
        ], self::cases());
    }
}
