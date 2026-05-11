<?php

namespace App\Enums;

enum StorageRackType: string
{
    case BLOOD = 'blood';
    case REAGENT = 'reagent';
    case TOOL = 'tool';
    case SAMPLE = 'sample';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::BLOOD => 'Blood',
            self::REAGENT => 'Reagent',
            self::TOOL => 'Tool',
            self::SAMPLE => 'Sample',
            self::OTHER => 'Other',
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
