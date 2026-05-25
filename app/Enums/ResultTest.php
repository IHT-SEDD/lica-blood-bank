<?php

namespace App\Enums;

enum ResultTest: string
{
    case INCOMPATIBLE_1 = 'incompatible_1+';
    case INCOMPATIBLE_2 = 'incompatible_2+';
    case INCOMPATIBLE_3 = 'incompatible_3+';
    case INCOMPATIBLE_4 = 'incompatible_4+';
    case COMPATIBLE = 'compatible';

    public function label(): string
    {
        return match ($this) {
            self::INCOMPATIBLE_1 => 'Incompatible 1+',
            self::INCOMPATIBLE_2 => 'Incompatible 2+',
            self::INCOMPATIBLE_3 => 'Incompatible 3+',
            self::INCOMPATIBLE_4 => 'Incompatible 4+',
            self::COMPATIBLE => 'Compatible',
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
