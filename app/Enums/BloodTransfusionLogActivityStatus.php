<?php

namespace App\Enums;

enum BloodTransfusionLogActivityStatus: string
{
    case REGISTERED = 'blood_transfusion_registered';
    case CHECKED_IN = 'blood_transfusion_checked_in';
    case FINISHED = 'blood_transfusion_finished';
    case COMPLETED = 'blood_transfusion_completed';
    case DELETED = 'blood_transfusion_deleted';
    case ARCHIVED = 'blood_transfusion_archived';
    case UPDATED = 'blood_transfusion_updated';
    case CROSSMATCH_FINISH = 'crossmatch_finished';

    case BLOOD_HOLD = 'blood_stock_hold';
    case BLOOD_RELEASE = 'blood_stock_released';
    case BLOOD_DELETED = 'blood_stock_deleted';
    case BLOOD_DONT_RELEASE = 'blood_stock_not_released';
    case APPROVE_INCOMPATIBLE = 'blood_stock_approved_incompatible';

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => '(TERDAFTAR)',
            self::CHECKED_IN => '(CHECKED IN)',
            self::FINISHED => '(CROSSMATCH SELESAI)',
            self::COMPLETED => '(TRANSAKSI SELESAI)',
            self::DELETED => '(DIHAPUS)',
            self::ARCHIVED => '(DIARSIP)',
            self::UPDATED => '(DIPERBAHARUI)',
            self::CROSSMATCH_FINISH => '(CROSSMATCH SELESAI)',

            self::BLOOD_HOLD => '(DARAH SEDANG DITAHAN)',
            self::BLOOD_RELEASE => '(DARAH DIKELUARKAN)',
            self::BLOOD_DELETED => '(DARAH DIHAPUS)',
            self::BLOOD_DONT_RELEASE => '(DARAH TIDAK DIKELUARKAN)',
            self::APPROVE_INCOMPATIBLE => '(INCOMPATIBLE DI APPROVE)',
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::REGISTERED => 'Transaksi %s: Berhasil terdaftar oleh %s.',
            self::CHECKED_IN => 'Transaksi %s: Berhasil dicheckin/diterima oleh user dengan username %s.',
            self::FINISHED => 'Transaksi %s: Crossmatch untuk no. labu %s, berhasil diselesaikan oleh user dengan username %s.',
            self::COMPLETED => 'Transaksi %s: Transaksi telah diselesaikan oleh user dengan username %s.',
            self::DELETED => 'Transaksi %s: Dihapus oleh user dengan username %s.',
            self::ARCHIVED => 'Transaksi %s: Diarsip oleh user dengan username %s.',
            self::UPDATED => 'Transaksi %s: Data berhasil diperbaharui oleh user dengan username %s.',
            self::CROSSMATCH_FINISH => 'Transaksi %s: Crossmatch untuk no. labu %s, berhasil diselesaikan oleh user dengan username %s.',

            self::BLOOD_HOLD => 'Transaksi %s: Darah dengan no. labu %s, status nya diubah menjadi ditahan oleh user dengan username %s',
            self::BLOOD_RELEASE => 'Transaksi %s: Darah dengan no. labu %s, telah dikeluarkan oleh user dengan username %s',
            self::BLOOD_DELETED => 'Transaksi %s: Darah dengan no. labu %s, telah dihapus oleh user dengan username %s',
            self::BLOOD_DONT_RELEASE => 'Transaksi %s: Darah dengan no. labu %s, tidak dikeluarkan oleh user dengan username %s',
            self::APPROVE_INCOMPATIBLE => 'Transaksi %s: Hasil incompatible disetujui oleh user dengan username %s, untuk darah dengan no. labu %s',
        };
    }
}
