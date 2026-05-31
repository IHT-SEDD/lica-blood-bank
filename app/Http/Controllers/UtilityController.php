<?php

namespace App\Http\Controllers;

use App\Services\UtilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected UtilityService $service
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Fungsi untuk mengambil data agar bisa ditampilkan di select ----------
    public function selectData(Request $request, string $select)
    {
        return response()->json(
            $this->service->getSelectData($request, $select)
        );
    }

    // ---------- Fungsi untuk mengambil data agar bisa ditampilkan di select ----------
    public function selectDataSpecial(Request $request, string $select, string $id)
    {
        return response()->json(
            $this->service->getSelectDataSpecial($request, $select, $id)
        );
    }

    // ---------- Fungsi untuk mengambil data secara batch agar bisa ditampilkan di select ----------
    public function selectBatchData(Request $request): JsonResponse
    {
        $keys = $request->input('keys', []);

        if (empty($keys) || !is_array($keys)) {
            return response()->json(['results' => []]);
        }

        $results = [];
        foreach ($keys as $key) {
            $fakeRequest = Request::create(
                "/utility/select/{$key}",
                'GET',
                ['q' => '']
            );

            try {
                $results[$key] = $this->service->getSelectData($fakeRequest, $key);
            } catch (\Throwable $e) {
                $results[$key] = ['results' => []];
            }
        }

        return response()->json($results);
    }

    // ---------- Fungsi untuk mengambil data per id ----------
    public function getDataById(Request $request, string $data, string $id)
    {
        return response()->json(
            $this->service->getDataById($request, $data, $id)
        );
    }
}
