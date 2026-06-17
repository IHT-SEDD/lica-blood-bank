<?php

namespace App\Enums;

enum BloodComponent: string
{
    case WB = 'WB';
    case PRC = 'PRC';
    case PRC_LK = 'PRC Leukodepleted';
    case TC = 'TC';
    case TC_APH = 'TC Apheresis';
    case FFP = 'FFP';
    case CRYO = 'CRYO';
    case WRC = 'WRC';
    case LP = 'LP';

    public function label(): string
    {
        return match ($this) {
            self::WB => 'Whole Blood',
            self::PRC => 'Packed Red Cells',
            self::PRC_LK => 'PRC Leukodepleted',
            self::TC => 'Trombocyte Concentrate',
            self::TC_APH => 'Trombocyte Concentrate Apheresis',
            self::FFP => 'Fresh Frozen Plasma',
            self::CRYO => 'Cryoprecipitate',
            self::WRC => 'Washed Red Cells',
            self::LP => 'Liquid Plasma',
        };
    }

    public function exportLabel(): string
    {
        return match ($this) {
            self::WB => 'Whole Blood (WB)',
            self::PRC => 'Packed Red Cell (PRC)',
            self::PRC_LK => 'PRC Leukodepleted',
            self::TC => 'Trombocyte Concentrate (TC)',
            self::TC_APH => 'Trombocyte Concentrate (TC) Apheresis',
            self::FFP => 'Fresh Frozen Plasma (FFP)',
            self::CRYO => 'Cryoprecipitate',
            self::WRC => 'Washed Red Cells (WRC)',
            self::LP => 'Liquid Plasma (LP)',
        };
    }

    public static function toSelect(): array
    {
        return array_map(fn($item) => [
            'id' => $item->value,
            'text' => $item->label(),
        ], self::cases());
    }

    public static function getById(string $value): ?string
    {
        return match ($value) {
            self::WB->value => 'Whole Blood',
            self::PRC->value => 'Packed Red Cells',
            self::PRC_LK->value => 'PRC Leukodepleted',
            self::TC->value => 'Trombocyte Concentrate',
            self::TC_APH->value => 'Trombocyte Concentrate Apheresis',
            self::FFP->value => 'Fresh Frozen Plasma',
            self::CRYO->value => 'Cryoprecipitate',
            self::WRC->value => 'Washed Red Cells',
            self::LP->value => 'Liquid Plasma',
            default => null,
        };
    }
}
