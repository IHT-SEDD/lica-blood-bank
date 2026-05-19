<?php

namespace App\Enums;

enum ResultTest : string
{
    case INCOMPOTIBLE_1 = 'incompotible_1++';
    case INCOMPOTIBLE_2 = 'incompotible_2++';
    case INCOMPOTIBLE_3 = 'incompotible_3++';
    case INCOMPOTIBLE_4 = 'incompotible_4++';
    case COMPATIBLE = 'compatible';

    public function label(): string
    {
        return match ($this) {
            self::INCOMPOTIBLE_1 => 'Incompatible 1++',
            self::INCOMPOTIBLE_2 => 'Incompatible 2++',
            self::INCOMPOTIBLE_3 => 'Incompatible 3++',
            self::INCOMPOTIBLE_4 => 'Incompatible 4++',
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
