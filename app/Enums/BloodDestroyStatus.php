<?php

namespace App\Enums;

enum BloodDestroyStatus: string
{
    case DESTROYED = 'destroyed';
    case DELETED = 'deleted';
    case RESTORED = 'restored';

    public function label(): string
    {
        return match ($this) {
            self::DESTROYED => 'Blood has Destroyed',
            self::DELETED => 'Blood has Deleted',
            self::RESTORED => 'Blood has Restored',
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
