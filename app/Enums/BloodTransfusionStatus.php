<?php

namespace App\Enums;

enum BloodTransfusionStatus : string
{
    case BLOOD_TRANSFUSION_REGISTERED = 'blood_transfusion_registered';
    case BLOOD_TRANSFUSION_CHECKED_IN = 'blood_transfusion_checked_in';
    case BLOOD_TRANSFUSION_FINISHED = 'blood_transfusion_finished';
    case BLOOD_TRANSFUSION_ARCHIEVED = 'blood_transfusion_archieved';
    case BLOOD_TRANSFUSION_DELETED = 'blood_transfusion_deleted';

    public function label(): string
    {
        return match ($this) {
            self::BLOOD_TRANSFUSION_REGISTERED => 'Blood Transfusion Registered',
            self::BLOOD_TRANSFUSION_CHECKED_IN => 'Blood Transfusion Checked In',
            self::BLOOD_TRANSFUSION_FINISHED => 'Blood Transfusion Finished',
            self::BLOOD_TRANSFUSION_ARCHIEVED => 'Blood Transfusion Archived',
            self::BLOOD_TRANSFUSION_DELETED => 'Blood Transfusion Deleted',
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
