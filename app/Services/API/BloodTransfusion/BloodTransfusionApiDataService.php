<?php

namespace App\Services\API\BloodTransfusion;

use App\Models\BloodTransfusion;
use App\Models\SimrsConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BloodTransfusionApiDataService
{
    public function sendResult(string $orderNumber): array
    {
        $transfusion = BloodTransfusion::with([
            'patient',
            'doctor',
            'room',
            'insurance',
            'blood_transfusion_details.blood_transfusion_detail_tests.test',
        ])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $payload = $this->buildPayload($transfusion);

        $url     = config('services.SIMRS_API.url');
        $apiKey  = config('services.SIMRS_API.api_key');
        $keyVal  = config('services.SIMRS_API.key_value');
        $keyWs   = config('services.SIMRS_API.key_ws');
        $timeout = (int) config('services.SIMRS_API.timeout');

        $response = Http::timeout($timeout)
            ->withHeaders([
                $apiKey => $keyVal,
                'key-ws' => $keyWs,
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);

        if (!$response->successful()) {
            Log::error('SIMRS send result failed', [
                'order_number' => $orderNumber,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException(
                'Failed to send result to SIMRS. HTTP ' . $response->status()
            );
        }

        return [
            'order_number' => $orderNumber,
            'simrs_response' => $response->json(),
            'payload_sent' => $payload,
        ];
    }

    // ---------- Helpers ----------
    private function buildPayload(BloodTransfusion $transfusion): array
    {
        $hasil = [];

        foreach ($transfusion->blood_transfusion_details as $detail) {
            foreach ($detail->blood_transfusion_detail_tests as $detailTest) {
                $hasil[] = [
                    'test_id' => $detailTest->test_id,
                    'test_name' => $detailTest->test->name ?? null,
                    'kode_jenis_tes' => $detailTest->generalCode ?? null,
                    'result' => $detailTest->result ?? null,
                    'result_status' => $detailTest->result_status ?? null,
                    'unit' =>  null,
                    'normal_value'  =>  null,
                    'notes' => $detailTest->notes ?? null,
                    'flag' => null,
                    'group_test' => null,
                    'package_id' => $detailTest->package_id ?? null,
                ];
            }
        }

        return [
            'no_ref' => $transfusion->order_number,
            'tgl_kirim' => now()->toDateTimeString(),
            'hasil' => $hasil,
        ];
    }
}
