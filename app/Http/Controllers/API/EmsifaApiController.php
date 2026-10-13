<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\API\ApiUtilityService;
use App\Services\API\Emsifa\EmsifaApiCheckService;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

#[Group('EMSIFA API')]
class EmsifaApiController extends Controller
{
 // ---------- Panggil semua service yang dibutuhkan ----------
 public function __construct(
  protected ApiUtilityService $apiUtilityService,
  protected EmsifaApiCheckService $apiCheckService,
 ) {}

 // ---------- Check connection ----------
 #[Endpoint(
  title: 'Check Connection',
  description: 'Cek koneksi ke API EMSIFA: status, jumlah endpoint, latency, dan success rate.'
 )]
 public function checkConnection(): JsonResponse
 {
  try {
   $result = $this->apiCheckService->checkConnection();

   $message = match ($result['status']) {
    'connected' => 'EMSIFA API connected',
    'degraded' => 'EMSIFA API partially available',
    default => 'EMSIFA API disconnected',
   };

   return $this->apiUtilityService->successResponse($message, $result);
  } catch (Throwable $e) {
   globalLogger('error', 'EmsifaApiController@checkConnection', [
    'message' => $e->getMessage(),
   ], 500, 'emsifaapicheck');
   return $this->apiUtilityService->errorResponse('Failed to check EMSIFA connection');
  }
 }
}
