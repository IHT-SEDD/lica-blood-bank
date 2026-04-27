<?php

namespace App\Enums;

enum BloodComponent: string
{
    case WB = 'WB';
    case PRC = 'PRC';
    case TC = 'TC';
    case FFP = 'FFP';
    case CRYO = 'CRYO';
    case WRC = 'WRC';

    public function label(): string
    {
        return match ($this) {
            self::WB => 'Whole Blood',
            self::PRC => 'Packed Red Cells',
            self::TC => 'Trombocyte Concentrate',
            self::FFP => 'Fresh Frozen Plasma',
            self::CRYO => 'Cryoprecipitate',
            self::WRC => 'Washed Red Cells',
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
