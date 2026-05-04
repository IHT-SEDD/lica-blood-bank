<?php

namespace App\Enums;

enum OrderLogActivityStatus: string
{
    case DRAFT_CREATED = 'draft_created';
    case DRAFT_CANCELLED = 'draft_cancelled';
    case DRAFT_DELETED = 'draft_deleted';

    case PO_FILE_GENERATED = 'po_file_generated';
    case PO_FILE_PRINTED = 'po_file_printer';
    case PO_FILE_DOWNLOADED = 'po_file_downloaded';

    case ORDER_CREATED = 'order_created';
    case ORDER_EDITED = 'order_edited';
    case ORDER_CANCELLED = 'order_cancelled';
    case ORDER_DELETED = 'order_deleted';
    case ORDER_DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT_CREATED => '(DRAFT)',
            self::DRAFT_CANCELLED => '(DRAFT CANCELLED)',

            self::PO_FILE_GENERATED => '(PO GENERATED)',
            self::PO_FILE_PRINTED => '(PO PRINTED)',
            self::PO_FILE_DOWNLOADED => '(PO DOWNLOADED)',

            self::ORDER_CREATED => '(ORDER CREATED)',
            self::ORDER_CANCELLED => '(ORDER CANCELLED)',
            self::ORDER_EDITED => '(ORDER EDITED)',
            self::ORDER_DELETED => '(ORDER DELETED)',
            self::ORDER_DONE => '(ORDER DONE)',
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::DRAFT_CREATED => '%s: Draft created successfully by User ID %s.',
            self::DRAFT_CANCELLED => '%s: Draft cancelled successfully by User ID %s.',
            self::DRAFT_DELETED => '%s: Draft deleted successfully by User ID %s.',

            self::PO_FILE_GENERATED => '%s: PO File generated successfully by User ID %s.',
            self::PO_FILE_PRINTED => '%s: PO File printed %s by User ID %s.',
            self::PO_FILE_DOWNLOADED => '%s: PO File downloaded %s by User ID %s.',

            self::ORDER_CREATED => '%s: Order created successfully by User ID %s.',
            self::ORDER_CANCELLED => '%s: Order cancelled successfully by User ID %s.',
            self::ORDER_EDITED => '%s: Order edited successfully by User ID %s.',
            self::ORDER_DELETED => '%s: Order deleted successfully by User ID %s.',
            self::ORDER_DONE => '%s: Order set to done successfully by User ID %s.',
        };
    }
}
