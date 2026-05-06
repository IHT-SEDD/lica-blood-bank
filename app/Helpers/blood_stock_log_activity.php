<?php

// ---------- Fungsi generate description untuk blood stock log activity :begin ----------

use App\Enums\BloodStockLogActivityStatus;

function generateBloodStockLogDescription(
 BloodStockLogActivityStatus $status,
 string $bagNumber,
 string $userId
): string {
 return $status->label() . ' ' . sprintf(
  $status->template(),
  $bagNumber,
  $userId
 );
}
// ---------- Fungsi generate description untuk blood stock log activity :begin ----------
