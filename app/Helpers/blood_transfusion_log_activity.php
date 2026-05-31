<?php

use App\Enums\BloodTransfusionLogActivityStatus;

function generateBloodTransfusionLogDescription(
 BloodTransfusionLogActivityStatus $status,
 string $bagNumber,
 string $userId
): string {
 return $status->label() . ' ' . sprintf(
  $status->template(),
  $bagNumber,
  $userId
 );
}
