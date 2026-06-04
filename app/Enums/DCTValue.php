<?php

namespace App\Enums;

enum DCTValue: string
{
    case NEG = 'Negative';
    case NEG_1P = 'Negative 1+';
    case NEG_2P = 'Negative 2+';
    case NEG_3P = 'Negative 3+';
    case NEG_4P = 'Negative 4+';

    case POS = 'Positive';
    case POS_1P = 'Positive 1+';
    case POS_2P = 'Positive 2+';
    case POS_3P = 'Positive 3+';
    case POS_4P = 'Positive 4+';

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
