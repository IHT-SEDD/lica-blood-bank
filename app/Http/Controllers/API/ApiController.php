<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\API\ApiUtilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan ----------
    public function __construct(
        protected ApiUtilityService $apiUtilityService,
    ) {}

    // ---------- Generate Token ----------
    public function generateToken(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $result = $this->apiUtilityService->generateToken($request->name);

        return response()->json([
            'status' => 'success',
            'message' => 'Token generated successfully. Store this token safely, it will not be shown again.',
            'data' => $result,
        ]);
    }
}
