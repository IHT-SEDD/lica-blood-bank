<?php

namespace App\Enums;

enum IncomingBloodStatus: string
{
    case STOCK_REGISTERED = 'stock_registered';
    case STOCK_READY = 'stock_ready';
    case INCOMING_STOCK_CANCELLED = 'incoming_stock_cancelled';
    case INCOMING_STOCK_DELETED = 'incoming_stock_deleted';

    public function label(): string
    {
        return match ($this) {
            self::STOCK_REGISTERED => 'Stock Registered',
            self::STOCK_READY => 'Stock Ready',
            self::INCOMING_STOCK_CANCELLED => 'Incoming Stock Cancelled',
            self::INCOMING_STOCK_DELETED => 'Incoming Stock Deleted',
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
