<?php

namespace App\Http\Controllers\API;

use App\Enums\BloodTransfusionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\NewBloodTransfusionRequest;
use App\Models\BloodTransfusion;
use App\Services\API\ApiUtilityService;
use App\Services\API\BloodTransfusion\BloodTransfusionApiAddService;
use App\Services\API\BloodTransfusion\BloodTransfusionApiUpdateService;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\SchemaName;


#[Group('Blood Transfusion API')]
class BloodTransfusionApiController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan ----------
    public function __construct(
        protected BloodTransfusionApiAddService $apiAddService,
        protected BloodTransfusionApiUpdateService $apiUpdateService,
        protected ApiUtilityService $apiUtilityService,
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
        try {
            $validated = $request->validated();
            $orderNumber = $validated['transaksi']['no_order'];
            $existing = BloodTransfusion::where('order_number', $orderNumber)->first();

            // ---- Jika transaksi dengan nomor order yang sama, maka update data
            if ($existing) {
                $isFinished = !is_null($existing->finish_at) || $existing->status === BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED;

                if ($isFinished) {
                    return $this->apiUtilityService->errorResponse(
                        'Blood transfusion order has already been finished and cannot be updated.'
                    );
                }
                $result = $this->apiUpdateService->updateData($existing, $validated);

                return $this->apiUtilityService->successResponse(
                    'Blood transfusion request updated successfully',
                    $result
                );
            }

            // ---- Jika transaksi belum ada, maka create data
            $result = $this->apiAddService->insertNewData($request->validated());
            return $this->apiUtilityService->successResponse(
                'Blood transfusion request created successfully',
                $result
            );
        } catch (\Throwable $e) {
            return $this->apiUtilityService->errorResponse(
                $e->getMessage(),
            );
        }
    }
}
