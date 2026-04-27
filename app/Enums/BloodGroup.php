<?php

namespace App\Enums;

enum BloodGroup: string
{
    case A = 'A';
    case B = 'B';
    case AB = 'AB';
    case O = 'O';

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
