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
    public function getDatatableData(string $publicId, int $draw, ?string $detailPublicId = null): array
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
        $detailQuery = BloodTransfusionDetail::with([
            'bloodPack:id,public_id,blood_group,blood_rhesus,blood_component',
            'bloodTransfusionDetailTests.test:id,name',
            'bloodStock:id,bag_number',
            'bloodTransfusionDetailTests.verifiedByUser:id,name',
            'bloodTransfusionDetailTests.validatedByUser:id,name',
        ])
            ->where('blood_transfusion_id', $transfusion->id)
            ->whereNull('deleted_at');

        // Filter by specific detail (bag) if provided
        if ($detailPublicId) {
            $detailQuery->where('public_id', $detailPublicId);
        }

        $details = $detailQuery->get();

        $rows = [];

        foreach ($details as $detail) {
            $tests = $detail->bloodTransfusionDetailTests ?? collect();

            if ($tests->isEmpty()) {
                // Tampilkan baris kosong agar detail tetap terlihat
                $rows[] = [
                    'detail_test_public_id' => null,
                    'bag_number'             => '-',
                    'test_name'             => '-',
                    'result_value'          => null,
                    'is_verified'           => false,
                    'is_validated'          => false,
                ];
                continue;
            }
            // dd($tests);
            foreach ($tests as $detailTest) {
                $rows[] = [
                    'detail_test_public_id' => $detailTest->public_id,
                    'test_name'             => $detailTest->test?->name ?? '-',
                    'bag_number'            => $detail->bloodStock?->bag_number ?? '-',
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

        if(!$detailTest->result){
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
        $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)
            ->whereNull('deleted_at')
            ->first();

        if (!$detail) {
            return [
                'success' => false,
                'message' => 'Detail record not found.',
            ];
        }

        // Ambil semua test untuk detail ini
        $tests = BloodTransfusionDetailTest::where('bt_detail_id', $detail->id)
            ->whereNull('deleted_at')
            ->get();

        if ($tests->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No tests found for this bag.',
            ];
        }

        // Validasi: semua test harus sudah ada result
        $missingResult = $tests->filter(fn($t) => empty($t->result));
        if ($missingResult->isNotEmpty()) {
            return [
                'success' => false,
                'message' => 'All tests must have a result before completing.',
            ];
        }

        // Validasi: semua test harus sudah verified
        $missingVerified = $tests->filter(fn($t) => empty($t->verified_at) || empty($t->verified_by_user_id));
        if ($missingVerified->isNotEmpty()) {
            return [
                'success' => false,
                'message' => 'All tests must be verified before completing.',
            ];
        }

        // Validasi: semua test harus sudah validated
        $missingValidated = $tests->filter(fn($t) => empty($t->validated_at) || empty($t->validated_by_user_id));
        if ($missingValidated->isNotEmpty()) {
            return [
                'success' => false,
                'message' => 'All tests must be validated before completing.',
            ];
        }

        // Tentukan result: jika semua compatible → Compatible, jika ada incompatible → Incompatible
        $allCompatible = $tests->every(fn($t) => $t->result === ResultTest::COMPATIBLE->value);

        $transfusionResult = $allCompatible ? 'Compatible' : 'Incompatible';

        $detail->update([
            'transfusion_result' => $transfusionResult,
        ]);

        return [
            'success' => true,
            'message' => "Test completed. Result: {$transfusionResult}.",
            'transfusion_result' => $transfusionResult,
        ];
    }
}
