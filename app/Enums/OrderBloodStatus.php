<?php

namespace App\Enums;

enum OrderBloodStatus: string
{
    case DRAFT = 'draft';
    case DRAFT_CANCELLED = 'draft_cancelled';

    case ORDER_CREATED = 'order_created';
    case ORDER_CANCELLED = 'order_cancelled';
    case ORDER_DELETED = 'order_deleted';

    case SOME_ORDER_STOCK_REGISTERED = 'some_order_stock_registered';
    case ALL_ORDER_STOCK_REGISTERED = 'all_order_stock_registered';

    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::DRAFT_CANCELLED => 'Draft Cancelled',

            self::ORDER_CREATED => 'Order Created',
            self::ORDER_CANCELLED => 'Order Cancelled',
            self::ORDER_DELETED => 'Order Deleted',

            self::SOME_ORDER_STOCK_REGISTERED => 'Some Stock Order Are Registered',
            self::ALL_ORDER_STOCK_REGISTERED => 'All Stock Order Are Registered',

            self::DONE => 'Done',
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
