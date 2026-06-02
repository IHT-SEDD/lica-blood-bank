<?php

namespace App\Services\API;

use App\Models\ApiConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiUtilityService
{
  // ---------- Fungsi menghasilkan token api ----------
  public function generateToken(string $name): array
  {
    ApiConfig::where('name', $name)->update(['is_active' => false]);
    $plainToken = Str::random(64);

    $apiConfig = ApiConfig::create([
      'name' => $name,
      'token' => hash('sha256', $plainToken),
      'is_active' => true,
    ]);

    return [
      'public_id' => $apiConfig->public_id,
      'name' => $apiConfig->name,
      'token' => $plainToken,
      'created_at' => $apiConfig->created_at,
    ];
  }

  // ---------- Fungsi memvalidasi token api ----------
  public function validateToken(Request $request): ?ApiConfig
  {
    $bearerToken = $request->bearerToken();
    if (!$bearerToken) {
      return null;
    }

    return ApiConfig::where('token', hash('sha256', $bearerToken))
      ->where('is_active', true)
      ->whereNull('deleted_at')
      ->first();
  }

  // ---------- Success Response ----------
  public function successResponse(string $message = 'Success', mixed $data = null, int $responseCode = 200): JsonResponse
  {
    return response()->json([
      'status' => true,
      'response_code' => $responseCode,
      'message' => $message,
      'data' => $data,
    ], $responseCode);
  }

  // ---------- Unauthorized Response ----------
  public function unauthorizedResponse(string $message = 'Unauthorized', mixed $data = null): JsonResponse
  {
    return response()->json([
      'status' => false,
      'response_code' => 401,
      'message' => $message,
      'data' => $data,
    ], 401);
  }

  // ---------- Authorized Response ----------
  public function authorizedResponse(string $message = 'Authorized', mixed $data = null): JsonResponse
  {
    return response()->json([
      'status' => true,
      'response_code' => 200,
      'message' => $message,
      'data' => $data,
    ], 200);
  }

  // ---------- Not Found Response ----------
  public function notFoundResponse(string $message = 'Data not found', mixed $data = null): JsonResponse
  {
    return response()->json([
      'status' => false,
      'response_code' => 404,
      'message' => $message,
      'data' => $data,
    ], 404);
  }

  // ---------- Error Response ----------
  public function errorResponse(string $message = 'Internal server error', mixed $data = null, int $responseCode = 500): JsonResponse
  {
    return response()->json([
      'status' => false,
      'response_code' => $responseCode,
      'message' => $message,
      'data' => $data,
    ], $responseCode);
  }
}
