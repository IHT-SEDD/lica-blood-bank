<?php

namespace App\Services\API\BloodTransfusion;

use App\Enums\BloodTransfusionStatus;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use App\Models\Doctor;
use App\Models\Insurance;
use App\Models\Package;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Test;
use App\Services\API\ApiUtilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BloodTransfusionApiAddService
{
    const CROSSMATCH_CODE = '2454';
    const CROSSMATCH_PATTERN = '/crossmatch|uji\s*silang/i';

    // ---------- Fungsi add data ----------
    public function insertNewData(array $payload): array
    {
        return DB::transaction(function () use ($payload) {
            $demografi = $payload['demografi'];
            $transaksi = $payload['transaksi'];
            $tests = $payload['tes'];
            $type = $this->generateType($transaksi['jenis']);
            $filteredTests = $this->resolveTests($tests);
            // --- Ini untuk mengambil component dari payload->tests, dimapping berdasarkan nama atau kode atau dari config
            $bloodData = $this->resolveBloodComponentAndQuantity($filteredTests);

            // ---------- Prepare demografi----------
            $demographic = $this->resolveDemographic($demografi, $transaksi, $type);
            $patient = $demographic['patient'];
            $doctor = $demographic['doctor'];
            $room = $demographic['room'];
            $insurance = $demographic['insurance'];

            // ---------- Create Blood Transfusion ----------
            $bloodRequestAt = $transaksi['tgl_permintaan'] . ' ' . $transaksi['jam_permintaan'];
            $transfusion = BloodTransfusion::create([
                'patient_id' => $patient->id,
                'insurance_id' => $insurance->id,
                'room_id' => $room->id,
                'doctor_id' => $doctor->id,
                'order_number' => $transaksi['no_order'],
                'blood_request_at' => $bloodRequestAt,
                'diagnosis' => $transaksi['diagnosis'] ?? null,
                'status' => BloodTransfusionStatus::BLOOD_TRANSFUSION_REGISTERED,
                'blood_quantity' => $bloodData['total_quantity'],
            ]);

            // ---------- Create BloodTransfusionDetail & DetailTest ----------
            $createdDetailTests = [];

            foreach ($bloodData['blood_components'] as $bloodComponent) {
                $package = Package::with(['package_tests'])->where('is_active', 1)
                    ->where('blood_component', $bloodComponent['component'])
                    ->first();

                for ($i = 0; $i < $bloodComponent['quantity']; $i++) {
                    $btDetail = BloodTransfusionDetail::create([
                        'blood_transfusion_id' => $transfusion->id,
                        'component' => $bloodComponent['component'],
                    ]);

                    foreach ($package->package_tests as $pkgTest) {
                        $btDetailTest = BloodTransfusionDetailTest::create([
                            'bt_detail_id' => $btDetail->id,
                            'test_id' => $pkgTest->test_id,
                            'package_id' => $pkgTest->package_id,
                            'type' => 'package',
                        ]);
                        $createdDetailTests[] = [
                            'blood_transfusion_detail' => $btDetail->toArray(),
                            'blood_transfusion_detail_test' => $btDetailTest->toArray(),
                            'detail_public_id' => $btDetail->public_id,
                            'detail_test_id' => $btDetailTest->id,
                            'test_id' => $pkgTest->test_id,
                        ];
                    }
                }
            }

            return [
                'transfusion_public_id' => $transfusion->public_id,
                'order_number' => $transfusion->order_number,
                'patient' => $patient->name,
                'detail_tests_created' => count($createdDetailTests),
            ];
        });
    }

    // ---------- Helpers----------
    public function generateType(?string $type): string
    {
        return match ($type) {
            'Rawat Jalan' => 'rawat_jalan',
            'Rawat Inap' => 'rawat_inap',
            'IGD' => 'igd',
            'Rujukan' => 'rujukan',
            default => throw new \InvalidArgumentException(
                'Invalid transaction type: ' . $type
            ),
        };
    }
    private function resolveDemographic(array $demografi, array $transaksi, string $type): array
    {
        // ---------- Ambil atau buat pasien baru ----------
        $patient = Patient::firstOrCreate(
            [
                'medrec' => $demografi['no_rkm_medis']
            ],
            [
                'name' => $demografi['nama_pasien'],
                'gender' => $demografi['jk'] === 'L' ? 'M' : 'F',
                'birthdate' => $demografi['tgl_lahir'],
                'address' => $demografi['alamat'] ?? null,
                'phone' => $demografi['no_telp'] ?? null,
                'is_active' => true,
            ]
        );

        // ---------- Ambil atau buat dokter baru ----------
        $doctor = Doctor::where('general_code', $transaksi['kode_dokter'])
            ->where('is_active', true)
            ->first();
        if (!$doctor) {
            $doctor = Doctor::create([
                'name' => $transaksi['dokter'],
                'general_code' => $transaksi['kode_dokter'],
                'is_active' => true,
            ]);
        }

        // ---------- Ambil atau buat ruangan baru ----------
        $room = Room::where('general_code', $transaksi['kode_ruangan'])
            ->where('class', $transaksi['kelas'])
            ->where('type', $type)
            ->where('is_active', true)
            ->first();
        if (!$room) {
            $room = Room::create([
                'name' => $transaksi['ruangan'],
                'general_code' => $transaksi['kode_ruangan'],
                'type' => $type,
                'class' => $transaksi['kelas'] ?? 2,
                'is_active' => true,
            ]);
        }

        // ---------- Ambil atau buat asuransi baru ----------
        $insurance = Insurance::where('general_code', $transaksi['kode_pembayaran'])
            ->where('is_active', true)
            ->first();
        if (!$insurance) {
            $insurance = Insurance::create([
                'name' => $transaksi['pembayaran'],
                'general_code' => $transaksi['kode_pembayaran'],
                'is_active' => true,
            ]);
        }

        return [
            'patient' => $patient,
            'doctor' => $doctor,
            'room' => $room,
            'insurance' => $insurance,
        ];
    }
    public function resolveTests(array $tests): array
    {
        $CROSSMATCH_CODE = self::CROSSMATCH_CODE;
        $CROSSMATCH_PATTERN = self::CROSSMATCH_PATTERN;

        $filtered = array_filter($tests, function ($tes) use ($CROSSMATCH_CODE, $CROSSMATCH_PATTERN) {
            if (isset($tes['kode_jenis_tes']) && trim($tes['kode_jenis_tes']) === $CROSSMATCH_CODE) {
                return false;
            }
            if (isset($tes['nama_tes']) && preg_match($CROSSMATCH_PATTERN, $tes['nama_tes'])) {
                return false;
            }
            return true;
        });

        $unique = [];
        foreach ($filtered as $tes) {
            $kode = $tes['kode_jenis_tes'] ?? null;
            if ($kode && !isset($unique[$kode])) {
                $unique[$kode] = $tes;
            }
        }

        return array_values($unique);
    }
    // --- Ini untuk mengambil component dari payload->tests, dimapping berdasarkan nama atau kode atau dari config
    public function resolveBloodComponentAndQuantity(array $filteredTests): array
    {
        $componentConfig = config('api.blood-component');
        $componentCounts = [];
        $totalQuantity = 0;

        foreach ($filteredTests as $tes) {
            $kode = trim($tes['kode_jenis_tes'] ?? '');
            $nama = $tes['nama_tes'] ?? '';
            $matched = null;

            foreach ($componentConfig as $componentKey => $config) {
                // --- Cek kode_jenis_tes apakah ada di config atau tidak. Jika ada ambil value componentnya
                if (!empty($kode) && in_array($kode, $config['general_codes'], true)) {
                    $matched = $componentKey;
                    break;
                }

                // --- Cek nama tes apakah ada di config atau tidak. Jika ada ambil value componentnya
                foreach ($config['keywords'] as $keyword) {
                    if (stripos($nama, $keyword) !== false) {
                        $matched = $componentKey;
                        break 2;
                    }
                }
            }

            if ($matched) {
                $componentCounts[$matched] = ($componentCounts[$matched] ?? 0) + 1;
                $totalQuantity++;
            }
        }

        $bloodComponents = [];
        foreach ($componentCounts as $component => $quantity) {
            $bloodComponents[] = [
                'component' => $component,
                'quantity' => $quantity,
            ];
        }

        return [
            'blood_components' => $bloodComponents,
            'total_quantity' => $totalQuantity,
        ];
    }
}
