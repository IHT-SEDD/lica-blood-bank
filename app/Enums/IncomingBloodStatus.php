<?php

namespace App\Enums;

enum IncomingBloodStatus: string
{
    case STOCK_REGISTERD = 'stock_registered';
    case STOCK_READY = 'stock_ready';
    case INCOMING_STOCK_CANCELLED = 'incoming_stock_cancelled';

    public function label(): string
    {
        return match ($this) {
            self::STOCK_REGISTERD => 'Stock Registered',
            self::STOCK_READY => 'Stock Ready',
            self::INCOMING_STOCK_CANCELLED => 'Incoming Stock Cancelled',
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
