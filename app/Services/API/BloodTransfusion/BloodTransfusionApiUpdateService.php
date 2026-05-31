<?php

namespace App\Services\API\BloodTransfusion;

use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use App\Models\Doctor;
use App\Models\Insurance;
use App\Models\Package;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BloodTransfusionApiUpdateService
{
    public function __construct(
        protected BloodTransfusionApiAddService $addService,
    ) {}

    // ---------- Fungsi update data ----------
    public function updateData(BloodTransfusion $transfusion, array $payload): array
    {
        return DB::transaction(function () use ($transfusion, $payload) {
            $demografi = $payload['demografi'];
            $transaksi = $payload['transaksi'];
            $tests = $payload['tes'];

            // ---------- Update demografi (patient) ----------
            $this->updatePatient($transfusion->patient, $demografi);

            // ---------- Update relasi transaksi (dokter, ruangan, asuransi) ----------
            $type = $this->addService->generateType($transaksi['jenis']);
            $this->updateTransactionRelations($transfusion, $transaksi, $type);

            // ---------- Update field langsung di BloodTransfusion ----------
            $this->updateTransfusionFields($transfusion, $transaksi);

            // ---------- Append blood component baru ----------
            $filteredTests = $this->addService->resolveTests($tests);
            $bloodData = $this->addService->resolveBloodComponentAndQuantity($filteredTests);
            $appendedDetails = $this->appendNewBloodComponents($transfusion, $bloodData);

            $transfusion->refresh();
            return [
                'transfusion_public_id' => $transfusion->public_id,
                'order_number' => $transfusion->order_number,
                'patient' => $transfusion->patient->name,
                'details_appended' => count($appendedDetails),
            ];
        });
    }

    // ---------- Helpers ----------
    private function updatePatient(Patient $patient, array $demografi): void
    {
        $incoming = [
            'name' => $demografi['nama_pasien'],
            'gender' => $demografi['jk'] === 'L' ? 'M' : 'F',
            'birthdate' => $demografi['tgl_lahir'],
            'address' => $demografi['alamat'] ?? null,
            'phone' => $demografi['no_telp'] ?? null,
        ];

        // Hanya update field yang benar-benar berbeda
        $diff = array_filter(
            $incoming,
            fn($value, $key) => $patient->$key !== $value,
            ARRAY_FILTER_USE_BOTH
        );
        if (!empty($diff)) {
            $patient->update($diff);
        }
    }
    private function updateTransactionRelations(BloodTransfusion $transfusion, array $transaksi, string $type): void
    {
        // ---------- Dokter ----------
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

        // ---------- Ruangan ----------
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

        // ---------- Asuransi ----------
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

        // Kumpulkan perubahan relasi ID yang berbeda saja
        $relationDiff = [];

        if ($transfusion->doctor_id !== $doctor->id) {
            $relationDiff['doctor_id'] = $doctor->id;
        }
        if ($transfusion->room_id !== $room->id) {
            $relationDiff['room_id'] = $room->id;
        }
        if ($transfusion->insurance_id !== $insurance->id) {
            $relationDiff['insurance_id'] = $insurance->id;
        }

        if (!empty($relationDiff)) {
            $transfusion->update($relationDiff);
        }
    }
    private function updateTransfusionFields(BloodTransfusion $transfusion, array $transaksi): void
    {
        $bloodRequestAt = $transaksi['tgl_permintaan'] . ' ' . $transaksi['jam_permintaan'];
        $incoming = [
            'blood_request_at' => $bloodRequestAt,
            'diagnosis' => $transaksi['diagnosis'] ?? null,
        ];
        $diff = array_filter(
            $incoming,
            fn($value, $key) => $transfusion->$key !== $value,
            ARRAY_FILTER_USE_BOTH
        );
        if (!empty($diff)) {
            $transfusion->update($diff);
        }
    }
    private function appendNewBloodComponents(BloodTransfusion $transfusion, array $bloodData): array
    {
        // Ambil komponen yang sudah ada di DB
        $existingComponents = $transfusion->details()
            ->pluck('component')
            ->toArray();

        $appended = [];
        foreach ($bloodData['blood_components'] as $bloodComponent) {
            $component = $bloodComponent['component'];

            // Skip jika komponen ini sudah pernah dibuat
            if (in_array($component, $existingComponents, true)) {
                continue;
            }

            $package = Package::with(['package_tests'])
                ->where('is_active', 1)
                ->where('blood_component', $component)
                ->first();
            if (!$package) {
                continue;
            }

            $btDetail = BloodTransfusionDetail::create([
                'blood_transfusion_id' => $transfusion->id,
                'component' => $component,
            ]);
            foreach ($package->package_tests as $pkgTest) {
                $btDetailTest = BloodTransfusionDetailTest::create([
                    'bt_detail_id' => $btDetail->id,
                    'test_id' => $pkgTest->test_id,
                    'package_id' => $pkgTest->package_id,
                    'type' => 'package',
                ]);

                $appended[] = [
                    'detail_public_id' => $btDetail->public_id,
                    'detail_test_id' => $btDetailTest->id,
                    'test_id' => $pkgTest->test_id,
                ];
            }

            // Update total quantity di transfusion
            $transfusion->increment('blood_quantity', $bloodComponent['quantity']);

            // Catat komponen yang sudah ditambahkan agar tidak duplikat
            // dalam iterasi yang sama (jika payload mengirim komponen sama 2x)
            $existingComponents[] = $component;
        }

        return $appended;
    }
}
