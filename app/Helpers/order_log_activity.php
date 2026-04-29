<?php

// ---------- Fungsi generate description untuk order log activity :begin ----------

use App\Enums\OrderLogActivityStatus;

function generateOrderLogDescription(
 OrderLogActivityStatus $status,
 string $poNumber,
 string $userId
): string {
 return $status->label() . ' ' . sprintf(
  $status->template(),
  $poNumber,
  $userId
 );
}
// ---------- Fungsi generate description untuk order log activity :begin ----------
