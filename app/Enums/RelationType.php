<?php

namespace App\Enums;

enum RelationType: string
{
    case HUSBAND = 'husband';
    case WIFE = 'wife';
    case SISTER = 'sister';
    case BROTHER = 'brother';
    case FATHER = 'father';
    case MOTHER = 'mother';
    case GRANDFATHER = 'grandfather';
    case GRANDMOTHER = 'grandmother';
    case CHILD = 'child';
    case OTHER = 'other';
    case FRIEND = 'friend';
    case STRANGER = 'stranger';

    public function label(): string
    {
        return match ($this) {
            self::HUSBAND => 'Husband',
            self::WIFE => 'Wife',
            self::SISTER => 'Sister',
            self::BROTHER => 'Brother',
            self::FATHER => 'Father',
            self::MOTHER => 'Mother',
            self::GRANDFATHER => 'Grandfather',
            self::GRANDMOTHER => 'Grandmother',
            self::CHILD => 'Child',
            self::FRIEND => 'Friend',
            self::OTHER => 'Other',
            self::STRANGER => 'Stranger',
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
