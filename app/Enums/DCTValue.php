<?php

namespace App\Enums;

enum DCTValue: string
{
    case POS_1 = '1+';
    case POS_2 = '2+';
    case POS_3 = '3+';
    case POS_4 = '4+';
    case NEG = 'Negative';

    public function label(): string
    {
        return $this->value;
    }

    public static function toSelect(): array
    {
        return array_map(fn($item) => [
            'id' => $item->value,
            'text' => $item->label(),
        ], self::cases());
    }
}
