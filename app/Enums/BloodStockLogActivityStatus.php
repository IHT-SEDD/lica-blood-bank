<?php

namespace App\Enums;

enum BloodStockLogActivityStatus: string
{
    case BLOOD_STOCK_CREATED_BY_MANUAL = 'blood_stock_created_by_manual';
    case BLOOD_STOCK_CREATED_BY_SCAN = 'blood_stock_created_by_scan';
    case BLOOD_STOCK_DELETED = 'blood_stock_deleted';
    case BLOOD_STOCK_PERMANENT_DELETED = 'blood_stock_permanent_deleted';
    case BLOOD_STOCK_UPDATED = 'blood_stock_updated';
    case BLOOD_STOCK_RESTORED = 'blood_stock_restored';
    case BLOOD_STOCK_RETURNED = 'blood_stock_returned';

    case BLOOD_STOCK_IN_USE = 'blood_stock_in_use';
    case BLOOD_STOCK_TAKEN_OUT = 'blood_stock_taken_out';
    case BLOOD_STOCK_EXPIRED = 'blood_stock_expired';
    case BLOOD_STOCK_DESTROYED = 'blood_stock_destroyed';
    case BLOOD_STOCK_UNDESTROYED = 'blood_stock_undestroyed';

    public function label(): string
    {
        return match ($this) {
            self::BLOOD_STOCK_CREATED_BY_MANUAL => '(PENAMBAHAN VIA MANUAL)',
            self::BLOOD_STOCK_CREATED_BY_SCAN => '(PENAMBAHAN VIA SCAN)',
            self::BLOOD_STOCK_DELETED => '(DIHAPUS)',
            self::BLOOD_STOCK_PERMANENT_DELETED => '(DIHAPUS PERMANEN)',
            self::BLOOD_STOCK_UPDATED => '(DIPERBAHARUI)',
            self::BLOOD_STOCK_RESTORED => '(DIPULIHKAN)',
            self::BLOOD_STOCK_RETURNED => '(DIKEMBALIKAN)',

            self::BLOOD_STOCK_IN_USE => '(SEDANG DIGUNAKAN)',
            self::BLOOD_STOCK_TAKEN_OUT => '(DIKELUARKAN)',
            self::BLOOD_STOCK_EXPIRED => '(KADALUARSA)',
            self::BLOOD_STOCK_DESTROYED => '(DIMUSNAHKAN)',
            self::BLOOD_STOCK_UNDESTROYED => '(BATAL DIMUSNAHKAN)',
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::BLOOD_STOCK_CREATED_BY_MANUAL => 'Stok Darah %s: Sukses ditambahkan dengan metode manual oleh user dengan username %s.',
            self::BLOOD_STOCK_CREATED_BY_SCAN => 'Stok Darah %s: Sukses ditambahkan dengan metode scan oleh user dengan username %s.',
            self::BLOOD_STOCK_DELETED => 'Stok Darah %s: Telah dihapus oleh user dengan username %s.',
            self::BLOOD_STOCK_PERMANENT_DELETED => 'Stok Darah %s: Telah dihapus permanen oleh user dengan username %s.',
            self::BLOOD_STOCK_UPDATED => 'Stok Darah %s: Sukses diperbaharui datanya oleh user dengan username %s.',
            self::BLOOD_STOCK_RESTORED => 'Stok Darah %s: Sukses dipulihkan oleh user dengan username %s.',
            self::BLOOD_STOCK_RETURNED => 'Stok Darah %s: Sukses dikembalikan ke kulkas oleh user dengan username %s.',

            self::BLOOD_STOCK_IN_USE => 'Stok Darah %s: Darah sedang digunakan pada pasien dengan nama %s.',
            self::BLOOD_STOCK_TAKEN_OUT => 'Stok Darah %s: Darah berhasil dikeluarkan oleh user dengan username %s untuk pasien dengan nama %s.',
            self::BLOOD_STOCK_EXPIRED => 'Stok Darah %s: Darah sudah expire/kadaluarsa, sukses dicatat oleh user dengan username %s.',
            self::BLOOD_STOCK_DESTROYED => 'Stok Darah %s: Darah sudah dimusnahkan oleh user dengan username %s.',
            self::BLOOD_STOCK_UNDESTROYED => 'Stok Darah %s: Darah berhasil dibatalkan pemusnahan oleh user dengan username %s.',
        };
    }
}
