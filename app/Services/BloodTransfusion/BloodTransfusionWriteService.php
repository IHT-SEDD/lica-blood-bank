<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionLogActivityStatus;
use App\Enums\BloodTransfusionStatus;
use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionLogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BloodTransfusionWriteService
{
    // ---------- Fungsi Check In ----------
    public function checkinTransaction(string $publicId): string
    {
        try {
            return DB::transaction(function () use ($publicId) {
                $transfusion = BloodTransfusion::where('public_id', $publicId)
                    ->lockForUpdate()
                    ->firstOrFail();
                if ($transfusion->lab_number) {
                    throw new \RuntimeException(
                        'This request has already been checked in.'
                    );
                }

                $datePrefix = now()->format('ymd');

                $lock = Cache::lock('generate_lab_number', 10);
                if (!$lock->get()) {
                    throw new \RuntimeException(
                        'System is currently processing another request, please try again in a moment.'
                    );
                }

                try {
                    $latestLabNumber = BloodTransfusion::where('lab_number', 'like', $datePrefix . '%')
                        ->lockForUpdate()
                        ->orderBy('lab_number', 'desc')
                        ->value('lab_number');

                    $nextSequence = $latestLabNumber ? ((int) substr($latestLabNumber, -3)) + 1 : 1;
                    $labNumber = $datePrefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

                    $transfusion->update([
                        'lab_number' => $labNumber,
                        'status' => BloodTransfusionStatus::BLOOD_TRANSFUSION_CHECKED_IN ?? $transfusion->status,
                        'checkin_by_user_id' => Auth::id(),
                    ]);

                    BloodTransfusionLogActivity::create([
                        'blood_transfusion_public_id' => $transfusion->public_id,
                        'payload' => $transfusion,
                        'status' => BloodTransfusionLogActivityStatus::CHECKED_IN,
                        'description' => generateBloodTransfusionLogDescription(
                            BloodTransfusionLogActivityStatus::CHECKED_IN,
                            $this->generateDescription($transfusion),
                            Auth::user()->id
                        ),
                        'created_by_user_name' => Auth::user()->name,
                        'timestamp' => now(),
                    ]);

                    globalLogger('info', 'Blood Transfusion Checked In Successfully!', [
                        'id' => $transfusion->public_id,
                        'payload' => $transfusion,
                    ], 200, 'updatebloodtransfusion');

                    return $labNumber;
                } finally {
                    $lock->release();
                }
            });
        } catch (\Exception $e) {
            globalLogger('error', 'Blood Transfusion Failed to Check In!', [
                'public_id' => $publicId,
                'error' => $e->getMessage(),
            ], 500, 'updatebloodtransfusion');
            throw $e;
        }
    }

    // ---------- Fungsi Hold Blood Pack ----------
    public function completeTransaction(string $publicId): void
    {
        try {
            DB::transaction(function () use ($publicId) {
                $transfusion = BloodTransfusion::where('public_id', $publicId)
                    ->lockForUpdate()
                    ->firstOrFail();
                if ($transfusion->finish_at) {
                    throw new \RuntimeException('This request has been completed');
                }

                $lock = Cache::lock('complete_transaction', 10);
                if (!$lock->get()) {
                    throw new \RuntimeException('System is currently processing another request, please try again in a moment.');
                }

                try {
                    $transfusion->update([
                        'finish_at' => now(),
                        'status' => BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED ?? $transfusion->status,
                        'finish_by_user_id' => Auth::id(),
                    ]);

                    BloodTransfusionLogActivity::create([
                        'blood_transfusion_public_id' => $transfusion->public_id,
                        'payload' => $transfusion,
                        'status' => BloodTransfusionLogActivityStatus::COMPLETED,
                        'description' => generateBloodTransfusionLogDescription(
                            BloodTransfusionLogActivityStatus::COMPLETED,
                            $this->generateDescription($transfusion),
                            Auth::user()->id
                        ),
                        'created_by_user_name' => Auth::user()->name,
                        'timestamp' => now(),
                    ]);

                    globalLogger('info', 'Blood Transfusion Completed Successfully!', [
                        'id' => $transfusion->public_id,
                        'payload' => $transfusion,
                    ], 200, 'completebloodtransfusion');
                } finally {
                    $lock->release();
                }
            });
        } catch (\Exception $e) {
            globalLogger('error', 'Blood Transfusion Failed to Complete!', [
                'public_id' => $publicId,
                'error' => $e->getMessage(),
            ], 500, 'completebloodtransfusion');
            throw $e;
        }
    }

    // ---------- Fungsi Hold Blood Pack ----------
    public function holdBloodPack(string $detailPublicId): void
    {
        try {
            DB::transaction(function () use ($detailPublicId) {
                $detail = $this->getLockedDetail($detailPublicId);
                if (!$detail->blood_stock_id) {
                    throw new \RuntimeException('Blood stock not assigned to this detail.');
                }
                $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::HOLD);

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'hold_at'),
                    'status' => BloodTransfusionLogActivityStatus::BLOOD_HOLD,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::BLOOD_HOLD,
                        $this->generateDescription($detail->bloodTransfusion),
                        Auth::user()->id
                    ),
                    'created_by_user_name' => Auth::user()->name,
                    'timestamp' => now(),
                ]);

                globalLogger('info', 'Blood Stock Hold Successfully!', [
                    'id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'hold_at'),
                ], 200, 'bloodstockactivity');
            });
        } catch (\Exception $e) {
            globalLogger('error', 'Blood Stock Failed to Hold!', [
                'detail_public_id' => $detailPublicId,
                'error' => $e->getMessage(),
            ], 500, 'bloodstockactivity');
            throw $e;
        }
    }

    // ---------- Fungsi Release Blood Pack ----------
    public function releaseBloodPack(string $detailPublicId): void
    {
        try {
            DB::transaction(function () use ($detailPublicId) {
                $detail = $this->getLockedDetail($detailPublicId, [
                    ['blood_release_status', false],
                ]);
                if (!$detail->blood_stock_id) {
                    throw new \RuntimeException('Blood stock not assigned to this detail.');
                }
                $detail->update(['blood_release_status' => true]);
                $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::TAKEN_OUT);

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'released_at'),
                    'status' => BloodTransfusionLogActivityStatus::BLOOD_RELEASE,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::BLOOD_RELEASE,
                        $this->generateDescription($detail->bloodTransfusion),
                        Auth::user()->id
                    ),
                    'created_by_user_name' => Auth::user()->name,
                    'timestamp' => now(),
                ]);

                globalLogger('info', 'Blood Stock Released Successfully!', [
                    'id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'released_at'),
                ], 200, 'bloodstockactivity');
            });
        } catch (\Exception $e) {
            globalLogger('error', 'Blood Stock Failed to Release!', [
                'detail_public_id' => $detailPublicId,
                'error' => $e->getMessage(),
            ], 500, 'bloodstockactivity');
            throw $e;
        }
    }

    // ---------- Fungsi UnRelease Blood Pack ----------
    public function unReleaseBloodPack(string $detailPublicId): void
    {
        try {
            DB::transaction(function () use ($detailPublicId) {
                $detail = $this->getLockedDetail($detailPublicId, [
                    ['blood_release_status', false],
                ]);
                if (!$detail->blood_stock_id) {
                    throw new \RuntimeException('Blood stock not assigned to this detail.');
                }
                $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::USED);

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'not_released_at'),
                    'status' => BloodTransfusionLogActivityStatus::BLOOD_DONT_RELEASE,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::BLOOD_DONT_RELEASE,
                        $this->generateDescription($detail->bloodTransfusion),
                        Auth::user()->id
                    ),
                    'created_by_user_name' => Auth::user()->name,
                    'timestamp' => now(),
                ]);

                globalLogger('info', 'Blood Stock Not Released Successfully!', [
                    'id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'not_released_at'),
                ], 200, 'bloodstockactivity');
            });
        } catch (\Exception $e) {
            globalLogger('error', 'Blood Stock Failed to Not Release!', [
                'detail_public_id' => $detailPublicId,
                'error' => $e->getMessage(),
            ], 500, 'bloodstockactivity');
            throw $e;
        }
    }

    // ---------- Fungsi Approve Incompatible ----------
    public function approveIncompatible(string $detailPublicId): void
    {
        try {
            DB::transaction(function () use ($detailPublicId) {
                $detail = $this->getLockedDetail($detailPublicId, [
                    ['blood_release_status', false],
                    ['is_print_incompatible_letter', true],
                    ['is_approval_incompatible', false],
                ]);

                $detail->update(['is_approval_incompatible' => true]);

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'approve_incompatible_at'),
                    'status' => BloodTransfusionLogActivityStatus::APPROVE_INCOMPATIBLE,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::APPROVE_INCOMPATIBLE,
                        $this->generateDescription($detail->bloodTransfusion),
                        Auth::user()->id
                    ),
                    'created_by_user_name' => Auth::user()->name,
                    'timestamp' => now(),
                ]);

                globalLogger('info', 'Incompatible Result Approved Succesfully!', [
                    'id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'approve_incompatible_at'),
                ], 200, 'incompatibleresult');
            });
        } catch (\Exception $e) {
            globalLogger('error', 'Incompatible Result Failed to Approve!', [
                'detail_public_id' => $detailPublicId,
                'error' => $e->getMessage(),
            ], 500, 'incompatibleresult');
            throw $e;
        }
    }

    // ---------- Fungsi Update Data ---------- 
    public function updateData(Request $request, string $id): array
    {
        DB::beginTransaction();
        try {
            $transfusion = BloodTransfusion::with(['patient', 'insurance', 'room', 'doctor', 'details'])->findOrFail($request->id);
            $transfusion->update([
                'insurance_id' => $request->insurance_id,
                'room_id' => $request->room_id,
                'doctor_id' => $request->doctor_id,
                'relation_name' => $request->relation_name,
                'relation_type' => $request->relation_type,
                'blood_request_at' => $request->blood_required_at,
                'is_dct' => $request->is_dct,
                'diagnosis' => $request->diagnosis,
            ]);
            if ($transfusion->patient_id) {
                $transfusion->patient->update([
                    'blood_group' => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                ]);

                if (!is_null($transfusion->patient->blood_group) && !is_null($transfusion->patient->blood_rhesus)) {
                    foreach ($transfusion->details as $bloodTransfusionDetail) {
                        $bloodPack = BloodPack::where('blood_group', $transfusion->patient->blood_group)->where('blood_rhesus', $transfusion->patient->blood_rhesus)
                            ->where('blood_component', $bloodTransfusionDetail->component)
                            ->first();
                        $availableStock = BloodStock::where('blood_pack_id', $bloodPack?->id)->where('blood_status', BloodStockStatus::AVAILABLE)
                            ->where('expiry_date', '>=', $transfusion->blood_request_at)
                            ->orderBy('expiry_date', 'asc')
                            ->first();
                        $bloodTransfusionDetail->update([
                            'blood_stock_id' => $availableStock?->id,
                            'blood_pack_id' => $bloodPack?->id,
                        ]);
                        if ($availableStock) {
                            $availableStock->update(['blood_status' => BloodStockStatus::IN_USE]);
                        }
                    }
                }
            }

            BloodTransfusionLogActivity::create([
                'blood_transfusion_public_id' => $transfusion->public_id,
                'payload' => $transfusion->fresh([
                    'patient',
                    'insurance',
                    'room',
                    'doctor',
                    'details'
                ]),
                'status' => BloodTransfusionLogActivityStatus::UPDATED,
                'description' => generateBloodTransfusionLogDescription(
                    BloodTransfusionLogActivityStatus::UPDATED,
                    $this->generateDescription($transfusion),
                    Auth::id()
                ),
                'created_by_user_name' => Auth::user()->name,
                'timestamp' => now(),
            ]);

            DB::commit();
            $transfusion->refresh();
            $data = [
                'public_id' => $transfusion->public_id,
                'blood_request_at' => $transfusion->blood_request_at ? \Carbon\Carbon::parse($transfusion->blood_request_at)->format('Y/m/d') : '-',
                'order_number' => $transfusion->order_number ?? '-',
                'lab_number' => $transfusion->lab_number ?? '-',
                'diagnosis' => $transfusion->diagnosis ?? '-',
                'patient' => [
                    'medrec' => $transfusion->patient->medrec ?? '-',
                    'name' => $transfusion->patient->name ?? '-',
                    'gender' => $transfusion->patient->gender === 'M' ? 'Male' : ($transfusion->patient->gender === 'F' ? 'Female' : '-'),
                    'email' => $transfusion->patient->email ?? '-',
                    'address' => $transfusion->patient->address ?? '-',
                    'age' => $transfusion->patient->birthdate ? \Carbon\Carbon::parse($transfusion->patient->birthdate)->diff(\Carbon\Carbon::now())->format('%yY/%mM/%dD') : '-',
                    'blood_group' => $transfusion->patient->blood_group ?? '-',
                    'blood_rhesus' => $transfusion->patient->blood_rhesus ?? '-',
                ],
                'room' => [
                    'name' => $transfusion->room->name ?? '-',
                    'type' => $transfusion->room->type ? str_replace('_', ' ', Str::kebab($transfusion->room->type)) : '-',
                ],
                'insurance' => ['name' => $transfusion->insurance->name ?? '-'],
                'doctor' => ['name' => $transfusion->doctor->name ?? '-'],
                'is_cito' => false,
            ];

            globalLogger('info', 'Blood transfusion request updated succesfully!', [
                'id' => $transfusion->id,
                'payload' => $transfusion,
            ], 200, 'updatebloodtransfusion');
            return [
                'success' => true,
                'code' => 200,
                'data' => ['message' => 'Blood request successfully updated.', 'data' => $data,]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            globalLogger('error', 'Blood transfusion request failed to update!', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
            ], 500, 'updatebloodtransfusion');
            return [
                'success' => false,
                'code' => 500,
                'data' => ['message' => 'Failed to update blood request.', 'error' => $e->getMessage(),]
            ];
        }
    }

    // ---------- Helpers ----------
    private function getLockedDetail(string $detailPublicId, array $conditions = []): BloodTransfusionDetail
    {
        return BloodTransfusionDetail::where('public_id', $detailPublicId)
            ->with(['bloodTransfusion'])
            ->when(!empty($conditions), fn($q) => $q->where($conditions))
            ->lockForUpdate()
            ->firstOrFail();
    }
    private function updateBloodStockStatus(int $bloodStockId, BloodStockStatus $status): void
    {
        BloodStock::where('id', $bloodStockId)
            ->lockForUpdate()
            ->firstOrFail()
            ->update(['blood_status' => $status]);
    }
    private function generateDescription(BloodTransfusion $transfusion): string
    {
        return match (true) {
            !empty($transfusion->order_number) => 'for order number ' . $transfusion->order_number,
            !empty($transfusion->lab_number) => 'for lab number ' . $transfusion->lab_number,
            default => 'for patient medrec ' . $transfusion->patient->medrec,
        };
    }
    private function generatePayloadLog(BloodTransfusionDetail $detail, string $actionAtField): array
    {
        return [
            'blood_transfusion' => [
                'id' => $detail->bloodTransfusion->id,
                'public_id' => $detail->bloodTransfusion->public_id,
                'patient' => [
                    'id' => $detail->bloodTransfusion->patient->id ?? null,
                    'medrec' => $detail->bloodTransfusion->patient->medrec ?? null,
                    'name' => $detail->bloodTransfusion->patient->name ?? null,
                ],
                'lab_number' => $detail->bloodTransfusion->lab_number,
                'order_number' => $detail->bloodTransfusion->order_number,
                'insurance_id' => $detail->bloodTransfusion->insurance_id,
                'room_id' => $detail->bloodTransfusion->room_id,
            ],

            'blood_transfusion_detail' => [
                'id' => $detail->id,
                'public_id' => $detail->public_id,
                'blood_stock_id' => $detail->blood_stock_id,
                'bag_number' => $detail->bloodStock->bag_number ?? null,
                'component' => $detail->component,
                'result' => $detail->crossmatch_result,
                $actionAtField => now(),
            ],
        ];
    }
}
