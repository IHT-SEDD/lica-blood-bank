<?php

namespace App\Enums;

enum OrderBloodStatus: string
{
    case ORDER_CREATED = 'order_created';
    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::ORDER_CREATED => 'Order Created',
            self::DONE => 'Done',
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
