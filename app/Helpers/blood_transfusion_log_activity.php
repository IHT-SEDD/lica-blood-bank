<?php

use App\Enums\BloodTransfusionLogActivityStatus;

function generateBloodTransfusionLogDescription(BloodTransfusionLogActivityStatus $status, mixed ...$params): string
{
 return $status->label() . ' ' . sprintf(
  $status->template(),
  ...$params
 );
}
