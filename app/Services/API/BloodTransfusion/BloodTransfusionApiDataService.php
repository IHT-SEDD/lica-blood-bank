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
            'blood_transfusion_details.bloodTransfusionDetailTests.test',
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
                'Accept' => 'application/json',
            ])
            ->post($url, $payload);

        if (!$response->successful()) {
            Log::error('SIMRS send result failed', [
                'order_number' => $orderNumber,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException(
                'Failed to send result to SIMRS. HTTP ' . $response->body()
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
            foreach ($detail->bloodTransfusionDetailTests as $detailTest) {
                $hasil[] = [
                    'test_id' => $detailTest->test_id,
                    'test_name' => $detailTest->test->name ?? null,
                    'kode_jenis_tes' => $detailTest->general_code ?? null,
                    'result' => $detailTest->result ?? null,
                    'unit' =>  null,
                    'nilai_normal'  =>  null,
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
