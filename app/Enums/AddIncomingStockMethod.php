<?php

namespace App\Enums;

enum AddIncomingStockMethod: string
{
    case Manual = 'manual';
    case Excel = 'excel';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Manually',
            self::Excel => 'Excel',
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
