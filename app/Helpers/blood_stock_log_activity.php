<?php

// ---------- Fungsi generate description untuk blood stock log activity :begin ----------

use App\Enums\BloodStockLogActivityStatus;

function generateBloodStockLogDescription(BloodStockLogActivityStatus $status, mixed ...$params): string
{
 return $status->label() . ' ' . sprintf(
  $status->template(),
  ...$params
 );
}
// ---------- Fungsi generate description untuk blood stock log activity :begin ----------
