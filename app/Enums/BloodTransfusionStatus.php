<?php

namespace App\Enums;

enum BloodTransfusionStatus: string
{
    case BLOOD_TRANSFUSION_REGISTERED = 'blood_transfusion_registered';
    case BLOOD_TRANSFUSION_CHECKED_IN = 'blood_transfusion_checked_in';
    case BLOOD_TRANSFUSION_FINISHED = 'blood_transfusion_finished';
    case BLOOD_TRANSFUSION_DELETED = 'blood_transfusion_deleted';
    case BLOOD_TRANSFUSION_CANCELED = 'blood_transfusion_canceled';

    public function label(): string
    {
        return match ($this) {
            self::BLOOD_TRANSFUSION_REGISTERED => 'Terdaftar',
            self::BLOOD_TRANSFUSION_CHECKED_IN => 'Checkin',
            self::BLOOD_TRANSFUSION_FINISHED => 'Selesai',
            self::BLOOD_TRANSFUSION_DELETED => 'Dihapus',
            self::BLOOD_TRANSFUSION_CANCELED => 'Dibatalkan',
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
