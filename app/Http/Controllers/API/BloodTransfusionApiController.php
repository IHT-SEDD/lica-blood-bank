<?php

namespace App\Http\Controllers\API;

use App\Enums\BloodTransfusionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\NewBloodTransfusionRequest;
use App\Models\BloodTransfusion;
use App\Services\API\ApiUtilityService;
use App\Services\API\BloodTransfusion\BloodTransfusionApiAddService;
use App\Services\API\BloodTransfusion\BloodTransfusionApiDataService;
use App\Services\API\BloodTransfusion\BloodTransfusionApiUpdateService;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\SchemaName;
use App\Services\Integrations\LogIntegrationService;


#[Group('Blood Transfusion API')]
class BloodTransfusionApiController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan ----------
    public function __construct(
        protected BloodTransfusionApiAddService $apiAddService,
        protected BloodTransfusionApiDataService $apiDataService,
        protected BloodTransfusionApiUpdateService $apiUpdateService,
        protected ApiUtilityService $apiUtilityService,
        protected LogIntegrationService $logIntegrationService
    ) {}

    // ---------- Insert New Request ----------
    #[Endpoint(
        operationId: 'newRequest',
        title: 'Insert New Request',
        description: 'Insert or Update Blood Transfusion Request',
        method: 'POST'
    )]
    public function newRequest(NewBloodTransfusionRequest $request): JsonResponse
    {
        globalLogger('info', '(API) Incoming blood transfusion request', [
            'payload' => $request->all(),
        ], 200, 'newbloodtransfusion');

        try {
            $validated = $request->validated();
            $orderNumber = $validated['transaksi']['no_order'];
            $existing = BloodTransfusion::where('order_number', $orderNumber)->first();

            // ---- Jika transaksi dengan nomor order yang sama, maka update data
            if ($existing) {
                $isFinished = !is_null($existing->finish_at) || $existing->status === BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED;

                if ($isFinished) {
                    return $this->apiUtilityService->errorResponse(
                        'Transaksi permintaan darah ini tidak bisa diperbaharui karena sudah selesai!'
                    );
                }
                $result = $this->apiUpdateService->updateData($existing, $request->all());

                return $this->apiUtilityService->successResponse(
                    'Transaksi permintaan darah sukses diperbaharui',
                    $result
                );
            }

            // ---- Jika transaksi belum ada, maka create data
            $result = $this->apiAddService->insertNewData($request->all());
            return $this->apiUtilityService->successResponse(
                'Transaksi permintaan darah berhasil ditambahkan',
                $result
            );
        } catch (\Throwable $e) {

            $this->logIntegrationService->insertData(
                'new_request',
                'failed',
                $e->getMessage(),
                $request->validated()
            );

            return $this->apiUtilityService->errorResponse(
                $e->getMessage(),
            );
        }
    }

    // ---------- Send Result ----------
    #[Endpoint(
        operationId: 'sendResult',
        title: 'Send Result to SIMRS',
        description: 'Send Blood Transfusion Result to SIMRS',
        method: 'POST'
    )]
    public function sendResult(string $orderNumber): JsonResponse
    {
        try {
            $transfusion = BloodTransfusion::where('order_number', $orderNumber)->first();

            if (!$transfusion) {
                return $this->apiUtilityService->errorResponse(
                    'Transaksi permintaan darah tidak ditemukan.'
                );
            }

            $isFinished = !is_null($transfusion->finish_at)
                || $transfusion->status === BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED;

            if (!$isFinished) {
                return $this->apiUtilityService->errorResponse(
                    'Transaksi permintaan darah ini belum selesai!'
                );
            }

            $result = $this->apiDataService->sendResult($orderNumber);

            $this->logIntegrationService->insertData(
                'send_result',
                'success',
                'Hasil permintaan darah sukses terkirim ke SIMRS',
                $result
            );

            globalLogger('info', '(API) Send result blood transfusion succesfully!', [
                'id' => $transfusion->id,
                'payload' => $transfusion,
            ], 200, 'apisendresult');

            return $this->apiUtilityService->successResponse(
                'Hasil permintaan darah sukses terkirim ke SIMRS',
                $result
            );
        } catch (\Throwable $e) {
            $this->logIntegrationService->insertData(
                'send_result',
                'failed',
                $e->getMessage(),
                ['order_number' => $orderNumber]
            );

            globalLogger('error', '(API) Send result blood transfusion failed to send!', [
                'payload' => null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500, 'apisendresult');

            return $this->apiUtilityService->errorResponse($e->getMessage());
        }
    }
}
