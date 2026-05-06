<?php

namespace App\Enums;

enum IncomingBloodLogActivityStatus: string
{
    case INCOMING_CREATED_BY_MANUAL = 'incoming_stock_created_by_manual';
    case INCOMING_CREATED_BY_EXCEL = 'incoming_stock_created_by_excel';
    case INCOMING_CANCELLED = 'incoming_stock_cancelled';
    case INCOMING_DELETED = 'incoming_stock_deleted';
    case INCOMING_RESTORED = 'incoming_stock_restored';

    case INCOMING_ALL_STOCK_READY = 'incoming_all_stock_ready';

    public function label(): string
    {
        return match ($this) {
            self::INCOMING_CREATED_BY_MANUAL => '(CREATE MANUAL)',
            self::INCOMING_CREATED_BY_EXCEL => '(CREATE EXCEL)',
            self::INCOMING_CANCELLED => '(CANCELLED)',
            self::INCOMING_DELETED => '(DELETED)',
            self::INCOMING_RESTORED => '(RESTORED)',

            self::INCOMING_ALL_STOCK_READY => '(ALL STOCK READY)',
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::INCOMING_CREATED_BY_MANUAL => 'Incoming Stock %s: Manually created successfully by User ID %s.',
            self::INCOMING_CREATED_BY_EXCEL => 'Incoming Stock %s: Created successfully via excel by User ID %s.',
            self::INCOMING_CANCELLED => 'Incoming Stock %s: Cancelled successfully by User ID %s.',
            self::INCOMING_DELETED => 'Incoming Stock %s: Deleted successfully by User ID %s.',
            self::INCOMING_RESTORED => 'Incoming Stock %s: Restored successfully by User ID %s.',

            self::INCOMING_ALL_STOCK_READY => 'All Incoming Stock %s: Are ready in storage. Logged successfully by User ID %s.',
        };
    }
}
