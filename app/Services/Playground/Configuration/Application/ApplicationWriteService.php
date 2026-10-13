<?php

namespace App\Services\Playground\Configuration\Application;

use App\Models\Setting;
use App\Models\SettingLogActivity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApplicationWriteService
{
 // ---------- Fungsi untuk mengubah status MFA (Multi-Factor Authentication) di pengaturan ----------
 // Return true jika status berubah, false jika tidak ada perubahan
 public function updateMfaStatus(
  User $user,
  bool $enabled,
  ?string $ipAddress = null,
  ?string $userAgent = null
 ): bool {
  $old = Setting::getBool('mfa_enabled', true);

  // Tidak ada perubahan -> tidak perlu menulis log
  if ($old === $enabled) {
   return false;
  }
  // Setting dan log disimpan dalam satu transaksi
  DB::transaction(function () use ($user, $old, $enabled, $ipAddress, $userAgent) {
   Setting::setBool('mfa_enabled', $enabled);

   SettingLogActivity::create([
    'setting_key' => 'mfa_enabled',
    'action' => $enabled ? 'enabled' : 'disabled',
    'old_value' => $old ? '1' : '0',
    'new_value' => $enabled ? '1' : '0',
    'user_id' => $user->id,
    'user_name' => $user->username,
    'ip_address' => $ipAddress,
    'user_agent' => $userAgent !== null ? substr($userAgent, 0, 1000) : null,
   ]);
  });

  return true;
 }
}
