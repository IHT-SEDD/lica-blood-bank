<?php

namespace App\Services\BloodTransfusion;

use App\Enums\ResultTest;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use Illuminate\Support\Facades\Auth;

class BloodTransfusionDetailTestService
{
    /**
     * Mengambil data test list untuk datatable berdasarkan public_id transfusion.
     * Menyertakan result_options dari Enum ResultTest agar frontend tidak perlu
     * mendefinisikan ulang opsi yang sudah ada di backend.
     */
    public function getDatatableData(string $publicId, int $draw): array
    {
        $transfusion = BloodTransfusion::where('public_id', $publicId)
            ->whereNull('deleted_at')
            ->first();

        if (!$transfusion) {
            return [
                'draw'            => $draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'result_options'  => ResultTest::toSelect(),
            ];
        }

        // Ambil semua detail beserta test-nya (satu detail bisa punya banyak tes)
        $details = BloodTransfusionDetail::with([
            'bloodPack:id,public_id,blood_group,blood_rhesus,blood_component',
            'bloodTransfusionDetailTests.test:id,name',
            'bloodTransfusionDetailTests.verifiedByUser:id,name',
            'bloodTransfusionDetailTests.validatedByUser:id,name',
        ])
            ->where('blood_transfusion_id', $transfusion->id)
            ->whereNull('deleted_at')
            ->get();

        $rows = [];

        foreach ($details as $detail) {
            $tests = $detail->bloodTransfusionDetailTests ?? collect();

            if ($tests->isEmpty()) {
                // Tampilkan baris kosong agar detail tetap terlihat
                $rows[] = [
                    'detail_test_public_id' => null,
                    'test_name'             => '-',
                    'result_value'          => null,
                    'is_verified'           => false,
                    'is_validated'          => false,
                ];
                continue;
            }

            foreach ($tests as $detailTest) {
                $rows[] = [
                    'detail_test_public_id' => $detailTest->public_id,
                    'test_name'             => $detailTest->test?->name ?? '-',
                    'result_value'          => $detailTest->result,   // raw enum value
                    'verified'           => !empty($detailTest->verified_at) && !empty($detailTest->verified_by_user_id),
                    'validated'          => !empty($detailTest->validated_at) && !empty($detailTest->validated_by_user_id),
                ];
            }
        }

        $total = count($rows);

        return [
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows,
            'result_options'  => ResultTest::toSelect(),  // opsi dropdown dari Enum
        ];
    }

    /**
     * Menyimpan hasil (result) ke record BloodTransfusionDetailTest.
     * Memvalidasi bahwa value yang dikirim adalah salah satu case Enum ResultTest.
     */
    public function updateResult(string $publicId, string $resultValue): array
    {
        // Validasi: pastikan value termasuk dalam Enum
        $enumCase = ResultTest::tryFrom($resultValue);

        if ($enumCase === null) {
            return [
                'success' => false,
                'message' => 'Invalid result value.',
            ];
        }

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
            'result' => $enumCase->value,
            'result_by_user_id' => Auth::id(),
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

        $detailTest->update([
            $field . '_at' => now(),
            $field . '_by_user_id' => Auth::id(),
        ]);

        return [
            'success' => true,
            'message' => ucfirst($field) . ' successfully updated.',
        ];
    }
}
