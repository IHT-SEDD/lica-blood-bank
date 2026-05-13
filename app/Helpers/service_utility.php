<?php

// ---------- Global fungsi untuk logging laravel :begin ----------

/**
 * Global logger helper
 *
 * @param string $statusLog (info, error, warning, dll)
 * @param string $message
 * @param array  $context
 * @param int|null $statusCode
 * @param string|null $channel
 */

use Illuminate\Support\Facades\Log;

if (!function_exists('globalLogger')) {
 function globalLogger(
  string $statusLog,
  ?string $message,
  array $context = [],
  ?int $statusCode = null,
  ?string $channel = null,
  $payload = null
 ) {
  // ---------- Tambahkan status code jika ada ----------
  if (!is_null($statusCode)) {
   $context['status_code'] = $statusCode;
  }

  // ---------- Tambahkan payload request jika ada ----------
  if (!is_null($payload)) {
   $context['payload'] = $payload;
  }

  // ---------- Default log channel jika kosong ----------
  $logger = $channel
   ? Log::channel($channel)
   : Log::channel(config('logging.default'));

  // ---------- Validasi level logging ----------
  $allowedLevels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

  // ---------- Default info jika status tidak ada di validasi ----------
  if (!in_array($statusLog, $allowedLevels)) {
   $statusLog = 'info';
  }

  // ---------- Eksekusi log ----------
  $logger->{$statusLog}($message, $context);
 }
}
// ---------- Global fungsi untuk logging laravel :end ----------
