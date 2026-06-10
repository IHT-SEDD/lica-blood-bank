<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodComponent;
use App\Enums\BloodStockLogActivityStatus;
use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionLogActivityStatus;
use App\Enums\BloodTransfusionStatus;
use App\Http\Requests\BloodTransfusion\UpdateBloodPacksRequest;
use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodStockLogActivity;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use App\Models\BloodTransfusionLogActivity;
use App\Models\Package;
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
                        'Pasien ini telah di checkin!'
                    );
                }

                $datePrefix = now()->format('ymd');

                $lock = Cache::lock('generate_lab_number', 10);
                if (!$lock->get()) {
                    throw new \RuntimeException(
                        'Sistem sedang melakukan permintaan anda, harap menunggu!'
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
                            Auth::user()->username
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
                    throw new \RuntimeException('Transaksi ini sudah diselesaikan!');
                }

                $lock = Cache::lock('complete_transaction', 10);
                if (!$lock->get()) {
                    throw new \RuntimeException('Sistem sedang melakukan permintaan anda, harap menunggu!');
                }

                try {
                    $transfusion->update([
                        'finish_at' => now(),
                        'archived_at' => now(),
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
                            Auth::user()->username
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
                    throw new \RuntimeException('Darah tidak digunakan untuk pemeriksaan pasien ini!');
                }
                $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::HOLD);

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'hold_at'),
                    'status' => BloodTransfusionLogActivityStatus::BLOOD_HOLD,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::BLOOD_HOLD,
                        $this->generateDescription($detail->bloodTransfusion),
                        $detail->bloodStock->bag_number,
                        Auth::user()->username
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
    public function releaseBloodPack(Request $request, string $detailPublicId): void
    {
        try {
            DB::transaction(function () use ($detailPublicId, $request) {
                $detail = $this->getLockedDetail($detailPublicId, [
                    ['blood_release_status', false],
                ]);
                if (!$detail->blood_stock_id) {
                    throw new \Exception('Darah tidak digunakan untuk pemeriksaan pasien ini!');
                }
                if (!$request->blood_received_by) {
                    throw new \Exception('Harap masukan penerima darah terlebih dahulu!');
                }
                if (!$request->blood_number) {
                    throw new \Exception('Harap masukan nomor darah terlebih dahulu!');
                }
                $bloodStock = $detail->bloodStock;
                if (!$bloodStock) {
                    throw new \Exception('Data stok darah tidak ditemukan!');
                }
                if (strtolower(trim($bloodStock->bag_number)) !== strtolower(trim($request->blood_number))) {
                    throw new \Exception('Nomor darah tidak sesuai dengan labu darah pasien ini!');
                }
                $detail->update([
                    'blood_release_status' => true,
                    'blood_released_by_user_id' => Auth::user()->id,
                    'blood_received_by' => $request->blood_received_by,
                    'blood_released_at' => now(),
                ]);
                $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::TAKEN_OUT);

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'released_at'),
                    'status' => BloodTransfusionLogActivityStatus::BLOOD_RELEASE,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::BLOOD_RELEASE,
                        $this->generateDescription($detail->bloodTransfusion),
                        $detail->bloodStock->bag_number,
                        Auth::user()->username
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

    // ---------- Fungsi Delete Blood Pack ----------
    public function deleteBloodPack(string $detailPublicId): void
    {
        try {
            DB::transaction(function () use ($detailPublicId) {
                $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)
                    ->with(['bloodTransfusion', 'bloodStock'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($detail->bloodTransfusion->status === BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED) {
                    throw new \RuntimeException('Darah tidak bisa dihapus karena transaksi sudah diselesaikan!');
                }
                if (!$detail->blood_stock_id) {
                    throw new \RuntimeException('Darah tidak digunakan untuk pemeriksaan pasien ini!');
                }
                if ($detail->blood_release_status === 1) {
                    throw new \RuntimeException('Darah tidak bisa dihapus karena sudah dikeluarkan!');
                }
                if ($detail->bloodStock->blood_status === BloodStockStatus::HOLD) {
                    throw new \RuntimeException('Darah tidak bisa dihapus karena sedang ditahan!');
                }

                // ---------- Tentukan status stok sebelum hapus ----------
                $newStatus = (!empty($detail->crossmatch_result) && $detail->crossmatch_finish_at !== null)
                    ? BloodStockStatus::USED
                    : BloodStockStatus::AVAILABLE;
                $this->updateBloodStockStatus($detail->blood_stock_id, $newStatus);

                $payloadLog = $this->generatePayloadLog($detail, 'deleted_at');
                $transfusion = $detail->bloodTransfusion;
                $bagNumber = $detail->bloodStock->bag_number;
                $description = $this->generateDescription($transfusion);
                $username = Auth::user()->username;
                $createdByName = Auth::user()->name;

                $detail->forceDelete();

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $transfusion->public_id,
                    'payload' => $payloadLog,
                    'status' => BloodTransfusionLogActivityStatus::BLOOD_DELETED,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::BLOOD_DELETED,
                        $description,
                        $bagNumber,
                        $username
                    ),
                    'created_by_user_name' => $createdByName,
                    'timestamp' => now(),
                ]);

                globalLogger('info', 'Blood Stock Deleted Successfully!', [
                    'id' => $transfusion->public_id,
                    'payload' => $payloadLog,
                ], 200, 'bloodstockactivity');
            });
        } catch (\Exception $e) {
            globalLogger('error', 'Blood Stock Failed to Delete!', [
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
                    throw new \RuntimeException('Darah tidak digunakan untuk pemeriksaan pasien ini!');
                }
                $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::USED);

                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                    'payload' => $this->generatePayloadLog($detail, 'not_released_at'),
                    'status' => BloodTransfusionLogActivityStatus::BLOOD_DONT_RELEASE,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::BLOOD_DONT_RELEASE,
                        $this->generateDescription($detail->bloodTransfusion),
                        $detail->bloodStock->bag_number,
                        Auth::user()->username
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
                        Auth::user()->username,
                        $detail->bloodStock->bag_number
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
                'dct_value' => $request->dct_value,
                'diagnosis' => $request->diagnosis,
            ]);
            if ($transfusion->patient_id) {
                $transfusion->patient->update([
                    'blood_group' => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                ]);

                if (!is_null($transfusion->patient->blood_group) && !is_null($transfusion->patient->blood_rhesus)) {
                    foreach ($transfusion->details as $bloodTransfusionDetail) {
                        $bloodPack = BloodPack::where('blood_group', $transfusion->patient->blood_group)
                            ->where('blood_rhesus', $transfusion->patient->blood_rhesus)
                            ->where('blood_component', $bloodTransfusionDetail->component)
                            ->first();
                        $availableStock = BloodStock::where('blood_pack_id', $bloodPack?->id)
                            ->where('blood_status', BloodStockStatus::AVAILABLE)
                            ->where('expiry_date', '>=', $transfusion->blood_request_at)
                            ->orderBy('expiry_date', 'asc')
                            ->first();
                        $bloodTransfusionDetail->update([
                            // 'blood_stock_id' => $availableStock?->id,
                            'blood_pack_id' => $bloodPack?->id,
                        ]);
                        // if ($availableStock) {
                        //     $availableStock->update(['blood_status' => BloodStockStatus::IN_USE]);
                        // }
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
                    Auth::user()->username
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
                'data' => ['message' => 'Data transaksi berhasil diperbaharui', 'data' => $data,]
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
                'data' => ['message' => 'Data transaksi gagal diperbaharui', 'error' => $e->getMessage(),]
            ];
        }
    }

    // ---------- Fungsi Update Data Darah ---------- 
    public function updateBloodPacks(UpdateBloodPacksRequest $request, string $id): array
    {
        try {
            $transfusion = BloodTransfusion::with(['patient'])->where('public_id', $id)->firstOrFail();

            DB::transaction(function () use ($transfusion, $request) {
                $selected_blood_components = $request->input('blood_packs');

                // ---------- Validasi awal sebelum proses apapun ----------
                foreach ($selected_blood_components as $component) {
                    if (!BloodComponent::getById($component['component_id'])) {
                        throw new \RuntimeException(
                            "Komponen darah '{$component['component_id']}' tidak ditemukan."
                        );
                    }

                    // ---------- Cek package hanya untuk komponen baru (tidak punya public_id) ----------
                    $isNewComponent = empty($component['public_id']);
                    if ($isNewComponent) {
                        $packageExists = Package::where('is_active', 1)
                            ->where('blood_component', $component['component_id'])
                            ->exists();

                        if (!$packageExists) {
                            throw new \RuntimeException(
                                "Paket pemeriksaan untuk komponen darah '{$component['component_id']}' tidak ditemukan atau tidak aktif."
                            );
                        }
                    }
                }

                // ---------- Ambil existing details ----------
                $existingDetails = BloodTransfusionDetail::with([
                    'bloodTransfusionDetailTests:id,public_id,bt_detail_id,test_id,result,result_by_user_id',
                ])
                    ->select(['id', 'public_id', 'component', 'blood_stock_id', 'blood_pack_id', 'blood_release_status', 'crossmatch_finish_at', 'crossmatch_result'])
                    ->where('blood_transfusion_id', $transfusion->id)
                    ->get()
                    ->keyBy(fn($d) => $d->component . '-' . $d->public_id);

                // ---------- Simpan existing tests untuk merge nanti ----------
                $existingTests = [];
                foreach ($existingDetails as $detail) {
                    foreach ($detail->bloodTransfusionDetailTests as $test) {
                        $key = $detail->public_id . '-' . $test->test_id;
                        $existingTests[$key] = [
                            'result' => $test->result,
                            'result_by_user_id' => $test->result_by_user_id,
                        ];
                    }
                }

                // ---------- Tentukan detail yang dihapus (tidak ada di submitted) ----------
                $submittedPublicIdSet = collect($selected_blood_components)
                    ->pluck('public_id')
                    ->filter()
                    ->flip()
                    ->toArray();

                foreach ($existingDetails as $detail) {
                    if (isset($submittedPublicIdSet[$detail->public_id])) continue;

                    // ---------- Blokir hapus jika darah sudah dikeluarkan ----------
                    if ($detail->blood_release_status == 1) {
                        throw new \RuntimeException(
                            "Komponen darah '{$detail->component}' tidak dapat dihapus karena sudah dikeluarkan."
                        );
                    }

                    // ---------- Kembalikan status stok ----------
                    if ($detail->blood_stock_id) {
                        $stock = BloodStock::where('id', $detail->blood_stock_id)->first();

                        if ($stock) {
                            $newStatus = (!empty($detail->crossmatch_result) && !empty($detail->crossmatch_finish_at))
                                ? BloodStockStatus::USED
                                : BloodStockStatus::AVAILABLE;

                            $stock->update([
                                'blood_status' => $newStatus,
                                'used_at' => null,
                            ]);

                            $user = Auth::user();

                            BloodStockLogActivity::create([
                                'blood_stock_public_id' => $stock->public_id,
                                'payload' => json_encode($stock->fresh()->toArray()),
                                'status' => BloodStockLogActivityStatus::BLOOD_STOCK_RETURNED,
                                'description' => generateBloodStockLogDescription(
                                    BloodStockLogActivityStatus::BLOOD_STOCK_RETURNED,
                                    $stock->bag_number,
                                    $user->username
                                ),
                                'created_by_user_name'  => $user->name,
                                'timestamp' => now(),
                            ]);

                            globalLogger('info', 'Blood stock returned successfully!', [
                                'data' => $stock,
                                'updated_by' => $user->id,
                            ], 200, 'editbloodstock');
                        }
                    }

                    $detail->forceDelete();
                }

                // ---------- Proses setiap komponen yang dikirim ----------
                $patient = $transfusion->patient;

                foreach ($selected_blood_components as $component) {
                    $keyComponent   = $component['component_id'] . '-' . $component['public_id'];
                    $existingDetail = $existingDetails->get($keyComponent);

                    // ---------- Existing detail dipertahankan ----------
                    if ($existingDetail) continue;

                    // ---------- Ambil package (sudah divalidasi di atas, pasti ada) ----------
                    $package = Package::with(['package_tests'])
                        ->where('is_active', 1)
                        ->where('blood_component', $component['component_id'])
                        ->first();

                    // ---------- Buat detail baru ----------
                    $transfusionDetail = BloodTransfusionDetail::create([
                        'blood_transfusion_id' => $transfusion->id,
                        'component' => $component['component_id'],
                        'blood_stock_id' => null,
                        'blood_pack_id' => null,
                        'crossmatch_result' => null,
                    ]);

                    // ---------- Auto-assign stok darah jika pasien punya golongan darah ----------
                    if (!is_null($patient?->blood_group) && !is_null($patient?->blood_rhesus)) {
                        $bloodPack = BloodPack::where('blood_group', $patient->blood_group)
                            ->where('blood_rhesus', $patient->blood_rhesus)
                            ->where('blood_component', $component['component_id'])
                            ->first();

                        $availableStock = BloodStock::where('blood_pack_id', $bloodPack?->id)
                            ->where('blood_status', BloodStockStatus::AVAILABLE)
                            ->where('expiry_date', '>=', $transfusion->blood_request_at)
                            ->orderBy('expiry_date', 'asc')
                            ->first();

                        $transfusionDetail->update([
                            'blood_pack_id' => $bloodPack?->id,
                            // 'blood_stock_id' => $availableStock?->id,
                        ]);

                        // if ($availableStock) {
                        //     $availableStock->update([
                        //         'blood_status' => BloodStockStatus::IN_USE,
                        //         'used_at'      => now(),
                        //     ]);
                        // }
                    }

                    // ---------- Buat test records ----------
                    foreach ($package->package_tests as $test) {
                        $lookupKey = $component['public_id'] . '-' . $test->test_id;
                        $existingTest = $existingTests[$lookupKey] ?? null;

                        BloodTransfusionDetailTest::create([
                            'bt_detail_id' => $transfusionDetail->id,
                            'test_id' => $test->test_id,
                            'type' => 'package',
                            'result' => $existingTest['result'] ?? null,
                            'result_by_user_id' => $existingTest['result_by_user_id'] ?? null,
                        ]);
                    }
                }

                // ---------- Log aktivitas transfusi (di dalam transaksi) ----------
                BloodTransfusionLogActivity::create([
                    'blood_transfusion_public_id' => $transfusion->public_id,
                    'payload' => $transfusion->fresh(['patient', 'insurance', 'room', 'doctor', 'details']),
                    'status' => BloodTransfusionLogActivityStatus::UPDATED,
                    'description' => generateBloodTransfusionLogDescription(
                        BloodTransfusionLogActivityStatus::UPDATED,
                        $this->generateDescription($transfusion),
                        Auth::user()->username
                    ),
                    'created_by_user_name' => Auth::user()->name,
                    'timestamp' => now(),
                ]);
            });

            globalLogger('info', 'Blood transfusion blood packs updated successfully!', [
                'id'      => $transfusion->id,
                'payload' => $transfusion->fresh(['patient', 'details']),
            ], 200, 'updatebloodtransfusion');

            return [
                'success' => true,
                'code'    => 200,
                'data'    => ['message' => 'Komponen darah berhasil diperbaharui.'],
            ];
        } catch (\RuntimeException $e) {
            return [
                'success' => false,
                'code'    => 422,
                'data'    => ['message' => $e->getMessage()],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'code'    => 500,
                'data'    => ['message' => 'Komponen darah gagal diperbaharui.', 'error' => $e->getMessage()],
            ];
        }
    }

    // ---------- Helpers ----------
    private function getLockedDetail(string $detailPublicId, array $conditions = []): BloodTransfusionDetail
    {
        return BloodTransfusionDetail::where('public_id', $detailPublicId)
            ->with(['bloodTransfusion', 'bloodStock'])
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
            !empty($transfusion->order_number) => 'dengan no. order ' . $transfusion->order_number,
            !empty($transfusion->lab_number) => 'dengan no. lab ' . $transfusion->lab_number,
            default => 'dengan medrec pasien ' . $transfusion->patient->medrec,
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
