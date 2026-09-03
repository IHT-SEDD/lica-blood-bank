<?php

namespace App\Enums;

enum LevelReactionTransfusion: string
{
    case SANGAT_BERAT = 'sangat_berat';
    case BERAT = 'berat';
    case SEDANG_BERAT = 'sedang_berat';
    case RINGAN_SEDANG = 'ringan_sedang';
    case SEDANG = 'sedang';
    case RINGAN = 'ringan';
    case NORMAL = 'normal';

    public function label(): string
    {
        return match ($this) {
            self::SANGAT_BERAT => 'Sangat Berat',
            self::BERAT => 'Berat',
            self::SEDANG_BERAT => 'Sedang - Berat',
            self::RINGAN_SEDANG => 'Ringan - Sedang',
            self::SEDANG => 'Sedang',
            self::RINGAN => 'Ringan',
            self::NORMAL => 'Normal',
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
