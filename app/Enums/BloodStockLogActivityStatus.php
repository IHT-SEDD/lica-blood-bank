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

    case BLOOD_STOCK_IN_USE = 'blood_stock_in_use';
    case BLOOD_STOCK_TAKEN_OUT = 'blood_stock_taken_out';
    case BLOOD_STOCK_EXPIRED = 'blood_stock_expired';
    case BLOOD_STOCK_DESTROYED = 'blood_stock_destroyed';

    public function label(): string
    {
        return match ($this) {
            self::BLOOD_STOCK_CREATED_BY_MANUAL => '(CREATE MANUAL)',
            self::BLOOD_STOCK_CREATED_BY_SCAN => '(CREATE SCAN)',
            self::BLOOD_STOCK_DELETED => '(DELETED)',
            self::BLOOD_STOCK_PERMANENT_DELETED => '(PERMANENT DELETED)',
            self::BLOOD_STOCK_UPDATED => '(UPDATED)',
            self::BLOOD_STOCK_RESTORED => '(RESTORED)',

            self::BLOOD_STOCK_IN_USE => '(IN USE)',
            self::BLOOD_STOCK_TAKEN_OUT => '(TAKEN OUT)',
            self::BLOOD_STOCK_EXPIRED => '(EXPIRED)',
            self::BLOOD_STOCK_DESTROYED => '(DESTROYED)',
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::BLOOD_STOCK_CREATED_BY_MANUAL => 'Blood Stock %s: Manually created successfully by User ID %s.',
            self::BLOOD_STOCK_CREATED_BY_SCAN => 'Blood Stock %s: Created successfully via scan by User ID %s.',
            self::BLOOD_STOCK_DELETED => 'Blood Stock %s: Deleted successfully by User ID %s.',
            self::BLOOD_STOCK_PERMANENT_DELETED => 'Blood Stock %s: Permanent deleted successfully by User ID %s.',
            self::BLOOD_STOCK_UPDATED => 'Blood Stock %s: Updated successfully by User ID %s.',
            self::BLOOD_STOCK_RESTORED => 'Blood Stock %s: Restored successfully by User ID %s.',

            self::BLOOD_STOCK_IN_USE => 'Blood Stock %s: Currently in use. Successfully logged by User ID %s.',
            self::BLOOD_STOCK_TAKEN_OUT => 'Blood Stock %s: Taken out, %s. Successfully logged by User ID %s.',
            self::BLOOD_STOCK_EXPIRED => 'Blood Stock %s: Has been expired. Successfully logged by User ID %s.',
            self::BLOOD_STOCK_DESTROYED => 'Blood Stock %s: Destroyed successfully by User ID %s.',
        };
    }
}
