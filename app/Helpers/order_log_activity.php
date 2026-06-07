<?php

// ---------- Fungsi generate description untuk order log activity :begin ----------

use App\Enums\OrderLogActivityStatus;

function generateOrderLogDescription(OrderLogActivityStatus $status, mixed ...$params): string
{
 return $status->label() . ' ' . sprintf(
  $status->template(),
  ...$params
 );
}
// ---------- Fungsi generate description untuk order log activity :begin ----------
