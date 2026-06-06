<?php

namespace App\Enums;

enum OrderLogActivityStatus: string
{
    case DRAFT_CREATED = 'draft_created';
    case DRAFT_CANCELLED = 'draft_cancelled';
    case DRAFT_DELETED = 'draft_deleted';

    case PO_FILE_GENERATED = 'po_file_generated';
    case PO_FILE_PRINTED = 'po_file_printed';
    case PO_FILE_DOWNLOADED = 'po_file_downloaded';

    case ORDER_CREATED = 'order_created';
    case ORDER_UPDATED = 'order_updated';
    case ORDER_EDITED = 'order_edited';
    case ORDER_CANCELLED = 'order_cancelled';
    case ORDER_DELETED = 'order_deleted';
    case ORDER_RESTORED = 'order_restored';

    case SOME_ORDER_STOCK_REGISTERED = 'some_order_stock_registered';
    case ALL_ORDER_STOCK_REGISTERED = 'all_order_stock_registered';
    case ORDER_DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT_CREATED => '(DRAFT)',
            self::DRAFT_CANCELLED => '(DRAFT BATAL)',

            self::PO_FILE_GENERATED => '(BUAT FILE PO)',
            self::PO_FILE_PRINTED => '(CETAK FILE PO)',
            self::PO_FILE_DOWNLOADED => '(DOWNLOAD FILE PO)',

            self::ORDER_CREATED => '(PERMINTAAN BARU)',
            self::ORDER_UPDATED => '(PERMINTAAN DIPERBAHARUI)',
            self::ORDER_CANCELLED => '(PERMINTAAN BATAL)',
            self::ORDER_EDITED => '(PERMINTAAN DIUBAH)',
            self::ORDER_DELETED => '(PERMINTAAN DIHAPUS)',
            self::ORDER_RESTORED => '(PERMINTAAN DIPULIHKAN)',
            self::ORDER_DONE => '(PERMINTAAN SELESAI)',

            self::SOME_ORDER_STOCK_REGISTERED => '(BEBERAPA DARAH PERMINTAAN DIDAFTARKAN)',
            self::ALL_ORDER_STOCK_REGISTERED => '(SEMUA DARAH PERMINTAAN DIDAFTARKAN)',
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::DRAFT_CREATED => '%s: Permintaan darah berhasil disimpan sebagai draft oleh user dengan username %s.',
            self::DRAFT_CANCELLED => '%s: Draft permintaan darah berhasil dibatalkan oleh user dengan username %s.',
            self::DRAFT_DELETED => '%s: Draft permintaan darah berhasil dihapus oleh user dengan username %s.',

            self::PO_FILE_GENERATED => '%s: File PO berhasil dibuat oleh user dengan username %s.',
            self::PO_FILE_PRINTED => '%s: File PO berhasil dicetak/diprint oleh user dengan username %s.',
            self::PO_FILE_DOWNLOADED => '%s: File PO berhasil didownload/diunduh oleh user dengan username %s.',

            self::ORDER_CREATED => '%s: Permintaan darah baru berhasil dibuat oleh user dengan username %s.',
            self::ORDER_UPDATED => '%s: Data permintaan darah berhasil diperbaharui oleh user dengan username %s.',
            self::ORDER_CANCELLED => '%s: Permintaan darah berhasil dibatalkan oleh user dengan username %s.',
            self::ORDER_EDITED => '%s: Data permintaan darah berhasil diubah oleh user dengan username %s.',
            self::ORDER_DELETED => '%s: Permintaan darah berhasil dihapus oleh user dengan username %s.',
            self::ORDER_RESTORED => '%s: Permintaan darah berhasil dipulihkan oleh user dengan username %s.',
            self::ORDER_DONE => '%s: Permintaan darah berhasil diselesaikan oleh user dengan username %s.',

            self::SOME_ORDER_STOCK_REGISTERED => '%s: Beberapa darah yang tertera pada permintaan berhasil didaftarkan pada sistem oleh user dengan username %s.',
            self::ALL_ORDER_STOCK_REGISTERED => '%s: Semua darah yang tertera pada permintaan berhasil didaftarkan pada sistem oleh user dengan username %s.',
        };
    }
}
