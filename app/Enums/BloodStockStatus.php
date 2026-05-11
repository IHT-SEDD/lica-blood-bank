<?php

namespace App\Enums;

enum BloodStockStatus: string
{
    case REGISTERED = 'registered';
    case AVAILABLE = 'available';
    case IN_USE = 'in_use';
    case ALREADY_TAKEN = 'already_taken';
    case TAKEN_OUT = 'taken_out';
    case EXPIRED = 'expired';
    case RETURNED = 'returned';
    case DESTROYED = 'destroyed';

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => 'Blood Registered',
            self::AVAILABLE => 'Available',
            self::IN_USE => 'Blood Currently in Use',
            self::ALREADY_TAKEN => 'Blood Already Taken',
            self::TAKEN_OUT => 'Blood Has Taken Out from Storage',
            self::EXPIRED => 'Expired',
            self::RETURNED => 'Blood Has Returned',
            self::DESTROYED => 'Blood has Destroyed',
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
