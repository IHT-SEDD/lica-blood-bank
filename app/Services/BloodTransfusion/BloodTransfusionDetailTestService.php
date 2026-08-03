<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodTransfusionLogActivityStatus;
use App\Enums\ResultTest;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use App\Models\BloodTransfusionLogActivity;
use App\Models\CrossMatchHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodTransfusionDetailTestService
{
    /**
     * Menyimpan hasil (result) ke record BloodTransfusionDetailTest.
     * Memvalidasi bahwa value yang dikirim adalah salah satu case Enum ResultTest.
     */
    public function updateResult(string $publicId, ?string $resultValue): array
    {
        // Validasi: pastikan value termasuk dalam Enum
        $enumCase = $resultValue ? ResultTest::tryFrom($resultValue) : null;

        // if ($enumCase === null) {
        //     return [
        //         'success' => false,
        //         'message' => 'Invalid result value.',
        //     ];
        // }

        $detailTest = BloodTransfusionDetailTest::where('public_id', $publicId)
            ->whereNull('deleted_at')
            ->first();

        if (!$detailTest) {
            return [
                'success' => false,
                'message' => 'Detail test record not found.',
            ];
        }

        $detailTest->update([
            'result' => $enumCase?->value,
            'result_by_user_id' => $enumCase ? Auth::id() : null,
            'validated_at' => null,
            'validated_by_user_id' => null,
            'verified_at' => null,
            'verified_by_user_id' => null,
        ]);

        return [
            'success' => true,
            'message' => 'Result successfully updated.',
        ];
    }

    public function updateVerifiedValidated($publicId, $field, $value): array
    {
        // Validasi: pastikan field termasuk dalam field yang diizinkan
        $allowedFields = ['verified', 'validated'];
        if (!in_array($field, $allowedFields, true)) {
            return [
                'success' => false,
                'message' => 'Invalid field.',
            ];
        }

        // Validasi: pastikan value adalah boolean
        if (!is_bool($value)) {
            return [
                'success' => false,
                'message' => 'Value must be a boolean.',
            ];
        }

        $detailTest = BloodTransfusionDetailTest::where('public_id', $publicId)
            ->whereNull('deleted_at')
            ->first();
        // dd($detailTest,$publicId, $field, $value);
        if (!$detailTest) {
            return [
                'success' => false,
                'message' => 'Detail test record not found.',
            ];
        }

        if (!$detailTest->result) {
            return [
                'success' => false,
                'message' => 'Result must be filled first.',
            ];
        }

        $detailTest->update([
            $field . '_at' => $value ? now() : null,
            $field . '_by_user_id' => $value ? Auth::id() : null,
        ]);

        return [
            'success' => true,
            'message' => ucfirst($field) . ' successfully updated.',
        ];
    }

    /**
     * Menyelesaikan pemeriksaan untuk satu detail (bag).
     * Syarat: semua test harus sudah memiliki result, verified, dan validated.
     * Jika semua result = compatible → transfusion_result = Compatible
     * Jika ada salah satu incompatible → transfusion_result = Incompatible
     */
    public function completeTest(string $detailPublicId): array
    {
        try {
            DB::beginTransaction();

            $detail = BloodTransfusionDetail::withoutTrashed()->where('public_id', $detailPublicId)
                ->with(['bloodStock', 'bloodTransfusion', 'bloodTransfusion.patient'])
                ->first();
            if (!$detail) {
                return ['success' => false, 'message' => 'Data detail transaksi tidak ditemukan'];
            }

            $tests = BloodTransfusionDetailTest::withoutTrashed()->with(['test:id,public_id,name'])
                ->where('bt_detail_id', $detail->id)
                ->get();
            if ($tests->isEmpty()) {
                return ['success' => false, 'message' => 'Tidak ada pemeriksaan untuk labu darah ini'];
            }

            // // Validasi: semua test harus sudah verified
            // $missingVerified = $requiredTests->filter(
            //     fn($t) => empty($t->verified_at) || empty($t->verified_by_user_id)
            // );
            // if ($missingVerified->isNotEmpty()) {
            //     return [
            //         'success' => false,
            //         'message' => 'All tests must be verified before completing.',
            //     ];
            // }

            // // Validasi: semua test harus sudah validated
            // $missingValidated = $requiredTests->filter(
            //     fn($t) => empty($t->validated_at) || empty($t->validated_by_user_id)
            // );
            // if ($missingValidated->isNotEmpty()) {
            //     return [
            //         'success' => false,
            //         'message' => 'All tests must be validated before completing.',
            //     ];
            // }

            // Validasi minimal 2 test harus memiliki hasil
            $completedTests = $tests->filter(
                fn($t) => !empty($t->result)
            );
            if ($completedTests->count() < 2) {
                return [
                    'success' => false,
                    'message' => 'Minimal 2 pemeriksaan harus memiliki hasil.',
                ];
            }

            // Penentuan crossmatch_result
            $hasIncompatible = $completedTests->contains(function ($t) {
                $result = strtolower(trim($t->result ?? ''));
                return str_starts_with($result, 'incompatible');
            });
            $hasCompatible = $completedTests->contains(function ($t) {
                $result = strtolower(trim($t->result ?? ''));
                return str_starts_with($result, 'compatible');
            });
            if ($hasIncompatible) {
                $crossmatchResult = 'Incompatible';
            } elseif ($hasCompatible) {
                $crossmatchResult = 'Compatible';
            } else {
                return [
                    'success' => false,
                    'message' => 'Tidak ditemukan hasil Compatible atau Incompatible untuk menentukan hasil crossmatch.',
                ];
            }
            $detail->update([
                'crossmatch_result' => $crossmatchResult,
                'crossmatch_finish_at' => now(),
            ]);

            $this->insertCrossMatchHistory($detail, $crossmatchResult);

            BloodTransfusionLogActivity::create([
                'blood_transfusion_public_id' => $detail->bloodTransfusion->public_id,
                'payload' => $detail,
                'status' => BloodTransfusionLogActivityStatus::CROSSMATCH_FINISH,
                'description' => generateBloodTransfusionLogDescription(
                    BloodTransfusionLogActivityStatus::CROSSMATCH_FINISH,
                    $this->generateDescription($detail->bloodTransfusion),
                    $detail->bloodStock->bag_number,
                    Auth::user()->username
                ),
                'created_by_user_name' => Auth::user()->name,
                'timestamp' => now(),
            ]);

            DB::commit();

            globalLogger('info', 'Crossmatch Test Finished Successfully!', [
                'id' => $detail->bloodTransfusion->public_id,
                'payload' => $detail,
            ], 200, 'donebloodtransfusion');
            return [
                'success' => true,
                'message' => "Pemeriksaan crossmatch berhasil diselesaikan dengan hasil: {$crossmatchResult}.",
                'crossmatch_result' => $crossmatchResult,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            globalLogger('error', 'Crossmatch Test Failed to Finished!', [
                'detail_public_id' => $detailPublicId,
                'error' => $th->getMessage(),
            ], 500, 'donebloodtransfusion');
            return [
                'success' => false,
                'message' => 'Pemeriksaan crossmatch gagal diselesaikan',
                'error' => $th->getMessage()
            ];
        }
    }

    private function insertCrossMatchHistory($detail, ?string $crossmatchResult)
    {
        try {
            DB::beginTransaction();
            if (is_null($detail)) return false;
            $history = CrossMatchHistory::where('blood_transfusion_detail_id', $detail->id)->first();
            if ($history) {
                $history->update([
                    'result' => $crossmatchResult,
                    'blood_stock_id' => $detail->blood_stock_id,
                    'updated_at' => now()
                ]);
            }

            $newlyCreated = CrossMatchHistory::create([
                'blood_transfusion_detail_id' => $detail->id,
                'blood_stock_id' => $detail->blood_stock_id,
                'result' => $crossmatchResult,
                'patient_name' => $detail->bloodTransfusion->patient->name
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Gagal update crossmatch_histories',
                'error' => $th->getMessage()
            ];
        }
    }
    private function generateDescription(BloodTransfusion $transfusion): string
    {
        return match (true) {
            !empty($transfusion->order_number) => 'dengan no. order ' . $transfusion->order_number,
            !empty($transfusion->lab_number) => 'dengan no. lab ' . $transfusion->lab_number,
            default => 'dengan medrec pasien ' . $transfusion->patient->medrec,
        };
    }
}
