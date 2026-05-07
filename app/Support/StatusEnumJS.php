<?php

namespace App\Support;

use App\Enums\IncomingBloodStatus;
use App\Enums\OrderBloodStatus;

class StatusEnumJS
{
 // ---------- ORDER BLOOD STATUS ----------
 public static function OrderBloodStatus(): array
 {
  return [
   'DONE' => OrderBloodStatus::DONE,
   'DRAFT' => OrderBloodStatus::DRAFT,
   'DRAFT_CANCELLED' => OrderBloodStatus::DRAFT_CANCELLED,
   'ORDER_CREATED' => OrderBloodStatus::ORDER_CREATED,
   'ORDER_CANCELLED' => OrderBloodStatus::ORDER_CANCELLED,
   'ORDER_DELETED' => OrderBloodStatus::ORDER_DELETED,
   'SOME_ORDER_STOCK_REGISTERED' => OrderBloodStatus::SOME_ORDER_STOCK_REGISTERED,
   'ALL_ORDER_STOCK_REGISTERED' => OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED,
  ];
 }

 // ---------- INCOMING BLOOD STATUS ----------
 public static function IncomingBloodStatus(): array
 {
  return [
   'STOCK_REGISTERED' => IncomingBloodStatus::STOCK_REGISTERED,
   'STOCK_READY' => IncomingBloodStatus::STOCK_READY,
   'INCOMING_STOCK_CANCELLED' => IncomingBloodStatus::INCOMING_STOCK_CANCELLED,
  ];
 }
}
