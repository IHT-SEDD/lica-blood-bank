<?php

namespace App\Enums;

enum OrderBloodStatus: string
{
    case ORDER_CREATED = 'order_created';
    case DONE = 'done';
}
