<?php

namespace App\Services\API\Emsifa;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmsifaApiCheckService
{
  private const BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/v2';
  private const TIMEOUT = 10; // detik

  // Batas klasifikasi latency (ms)
  private const FAST_MS = 500;
  private const NORMAL_MS = 1000;

  /**
   * Daftar endpoint EMSIFA yang dicek.
   * ID sampel: Banda Aceh (11) -> Tapak Tuan (11.01) -> Bakongan (11.01.01) -> Keude Bakongan (11.01.01.2001) -> Kode Pos 40152
   */
  private function endpoints(): array
  {
    return [
      // Provinsi
      'list-provinces' => ['label' => 'Daftar Provinsi', 'path' => '/provinces.json'],
      'detail-provinces' => ['label' => 'Detail Provinsi', 'path' => '/provinces/11.json'],
      // Kabupaten / Kota
      'list-regencies-by-province' => ['label' => 'Daftar Kab/Kota', 'path' => '/regencies/11.json'],
      'detail-regency' => ['label' => 'Detail Kab/Kota', 'path' => '/regencies/11.01.json'],
      // Kecamatan
      'list-districts-by-regency' => ['label' => 'Daftar Kecamatan', 'path' => '/districts/11.01.json'],
      'detail-district' => ['label' => 'Detail Kecamatan', 'path' => '/districts/11.01.01.json'],
      // Desa
      'list-villages-by-district' => ['label' => 'Daftar Desa', 'path' => '/villages/11.01.01.json'],
      'detail-village' => ['label' => 'Detail Desa', 'path' => '/villages/11.01.01.2001.json'],
      // Kelurahan berdasarkan kode pos
      'postal-code' => ['label' => 'Daftar Kelurahan Berdasarakan Kode Pos', 'path' => '/postal-codes/40152.json'],
      // Missing
      'missing' => ['label' => 'Daftar Wilayah Belum Lengkap', 'path' => '/missings.json'],
    ];
  }

  // ---------- Check connection ----------
  public function checkConnection(): array
  {
    $endpoints = $this->endpoints();
    $keys = array_keys($endpoints);

    try {
      $startedAt = microtime(true);

      $rawResponses = Http::pool(function (Pool $pool) use ($endpoints) {
        $requests = [];
        foreach ($endpoints as $endpoint) {
          $requests[] = $pool
            ->timeout(self::TIMEOUT)
            ->acceptJson()
            ->get(self::BASE_URL . $endpoint['path']);
        }
        return $requests;
      });

      $totalDurationMs = (int) round((microtime(true) - $startedAt) * 1000);

      $responses = [];
      foreach ($keys as $i => $key) {
        $responses[$key] = $rawResponses[$i] ?? null;
      }

      return $this->buildResult($endpoints, $responses, $totalDurationMs);
    } catch (Throwable $e) {
      Log::error('EMSIFA check connection failed', ['message' => $e->getMessage()]);

      return $this->buildResult($endpoints, [], 0);
    }
  }

  // ---------- Susun hasil ----------
  private function buildResult(array $endpoints, array $responses, int $totalDurationMs): array
  {
    $details = [];
    $latencies = [];
    $totalProvinces = null;

    foreach ($endpoints as $key => $endpoint) {
      $response = $responses[$key] ?? null;
      $isOk = $response instanceof Response && $response->successful();

      // total_time dari cURL (detik) -> ms. Null jika tidak tersedia / gagal
      $latencyMs = null;
      if ($response instanceof Response) {
        $totalTime = $response->handlerStats()['total_time'] ?? null;
        $latencyMs = $totalTime !== null ? (int) round($totalTime * 1000) : null;
      }

      if ($isOk && $latencyMs !== null) {
        $latencies[] = $latencyMs;
      }

      if ($key === 'list-provinces' && $isOk) {
        $totalProvinces = count($response->json("data") ?? []);
      }

      $details[] = [
        'key' => $key,
        'label' => $endpoint['label'],
        'path' => $endpoint['path'],
        'ok' => $isOk,
        'status' => $response instanceof Response ? $response->status() : null,
        'latency_ms' => $latencyMs,
        'error' => match (true) {
          $response instanceof ConnectionException => $response->getMessage(),
          $response instanceof Response && ! $response->successful() => 'HTTP ' . $response->status(),
          $response === null => 'No response',
          default => null,
        }
      ];
    }

    $total = count($endpoints);
    $healthy = count(array_filter($details, fn($d) => $d['ok']));
    $avgMs = $latencies ? (int) round(array_sum($latencies) / count($latencies)) : null;

    $status = match (true) {
      $healthy === $total => 'connected',
      $healthy > 0 => 'degraded',
      default => 'disconnected',
    };

    return [
      'status' => $status,
      'is_connected' => $healthy > 0,
      'total_endpoints' => $total,
      'healthy_endpoints' => $healthy,
      'success_rate' => $total > 0 ? round(($healthy / $total) * 100, 1) : 0,
      'total_provinces' => $totalProvinces,
      'latency' => [
        'average_ms' => $avgMs,
        'fastest_ms' => $latencies ? min($latencies) : null,
        'slowest_ms' => $latencies ? max($latencies) : null,
        'rating' => $this->rateLatency($avgMs),
      ],
      'total_duration_ms' => $totalDurationMs,
      'checked_at' => now()->toIso8601String(),
      'endpoints' => $details,
    ];
  }

  private function rateLatency(?int $ms): ?string
  {
    return match (true) {
      $ms === null => null,
      $ms < self::FAST_MS => 'fast',
      $ms < self::NORMAL_MS => 'normal',
      default => 'slow',
    };
  }
}
