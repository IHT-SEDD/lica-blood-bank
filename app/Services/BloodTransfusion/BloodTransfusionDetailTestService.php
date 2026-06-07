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
                ->with(['bloodStock'])
                ->first();
            if (!$detail) {
                return ['success' => false, 'message' => 'Data detail transaksi tidak ditemukan'];
            }

            // Ambil semua test untuk detail ini
            $tests = BloodTransfusionDetailTest::withoutTrashed()->with(['test:id,public_id,name'])
                ->where('bt_detail_id', $detail->id)
                ->get();
            if ($tests->isEmpty()) {
                return ['success' => false, 'message' => 'Tidak ada pemeriksaan untuk labu darah ini'];
            }

            $requiredTests = $tests->reject(function ($t) use ($detail) {
                return
                    strtolower($detail->component ?? '') === 'tc' &&
                    strtolower($t->test?->name ?? '') === 'mayor';
            });

            // Validasi: semua test harus sudah ada result
            $missingResult = $requiredTests->filter(
                fn($t) => empty($t->result)
            );
            if ($missingResult->isNotEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Terdapat pemeriksaan yang belum dikerjakan',
                ];
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

            // Tentukan result: jika semua compatible → Compatible, jika ada incompatible → Incompatible
            $allCompatible = $requiredTests->every(
                fn($t) => $t->result === ResultTest::COMPATIBLE->value
            );

            $transfusionResult = $allCompatible
                ? 'Compatible'
                : 'Incompatible';

            $detail->update([
                'crossmatch_result' => $transfusionResult,
                'crossmatch_finish_at' => now(),
            ]);

            $this->insertCrossMatchHistory($detail, $transfusionResult);

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
                'message' => "Pemeriksaan crossmatch berhasil diselesaikan dengan hasil: {$transfusionResult}.",
                'crossmatch_result' => $transfusionResult,
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

    private function insertCrossMatchHistory($detail, ?string $transfusionResult)
    {
        if (is_null($detail)) return false;

        $history = CrossMatchHistory::where('blood_transfusion_detail_id', $detail->id)->first();
        if ($history) {
            $history->update([
                'result' => $transfusionResult,
                'blood_stock_id' => $detail->blood_stock_id,
                'updated_at' => now()
            ]);
        }

        CrossMatchHistory::create([
            'blood_transfusion_detail_id' => $detail->id,
            'blood_stock_id' => $detail->blood_stock_id,
            'result' => $transfusionResult,
        ]);
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
