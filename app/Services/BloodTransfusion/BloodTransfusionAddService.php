<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodTransfusionLogActivityStatus;
use App\Enums\BloodTransfusionStatus;
use App\Models\BloodPack;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use App\Models\BloodTransfusionLogActivity;
use App\Models\Package;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodTransfusionAddService
{
    // ---------- Fungsi add data ----------
    public function newRequest(Request $request)
    {
        $selected_blood_components = json_decode($request->selected_blood_components);
        if (empty($selected_blood_components)) {
            return ['success' => false, 'code' => 422, 'data' => ['message' => 'At least one blood pack must be selected.',]];
        }

        DB::beginTransaction();
        try {
            if (!empty($request->patient_id)) {
                $patient = Patient::findOrFail($request->patient_id);
                $patient->update([
                    'name' => $request->name,
                    'gender' => $request->gender,
                    'birthdate' => $request->birthdate,
                    'email' => $request->email,
                    'phone' => $request->phone_number,
                    'blood_group' => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                    'address' => $request->address,
                ]);
            } else {
                $patient = Patient::create([
                    'name' => $request->name,
                    'gender' => $request->gender,
                    'birthdate' => $request->birthdate,
                    'email' => $request->email,
                    'phone' => $request->phone_number,
                    'blood_group' => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                    'address' => $request->address,
                    'is_active' => true,
                ]);
            }

            $transfusion = BloodTransfusion::create([
                'patient_id' => $patient->id,
                'insurance_id' => $request->insurance_id,
                'room_id' => $request->room_id,
                'doctor_id' => $request->doctor_id,
                'relation_name' => $request->relation_name,
                'relation_type' => $request->relation_type,
                'blood_request_at' => $request->blood_required_at,
                'diagnosis' => $request->diagnosis,
                'status' => BloodTransfusionStatus::BLOOD_TRANSFUSION_REGISTERED,
                'blood_quantity' => count($selected_blood_components),
            ]);

            $package = Package::with(['package_tests'])->where('is_active', 1)->first();
            if (empty($package)) {
                return ['success' => false, 'code' => 422, 'data' => ['message' => 'Package Test with code',]];
            }

            foreach ($selected_blood_components as $component) {
                $transfusionDetail = BloodTransfusionDetail::create([
                    'blood_transfusion_id' => $transfusion->id,
                    'component' => $component?->id,
                ]);

                if (!is_null($patient->blood_group) && !is_null($patient->blood_rhesus)) {
                    $bloodPack = BloodPack::where('blood_group', $patient->blood_group)
                        ->where('blood_rhesus', $patient->blood_rhesus)
                        ->where('blood_component', $component->id)
                        ->first();
                    $transfusionDetail->update(['blood_pack_id' => $bloodPack?->id,]);
                }

                foreach ($package->package_tests as $test) {
                    BloodTransfusionDetailTest::create([
                        'bt_detail_id' => $transfusionDetail->id,
                        'test_id' => $test->test_id,
                        'type' => 'package',
                    ]);
                }
            }

            $description = match (true) {
                !empty($transfusion->order_number) => 'dengan nomor order ' . $transfusion->order_number,
                !empty($transfusion->lab_number) => 'dengan nomor BDRS ' . $transfusion->lab_number,
                default => 'dengan nomor rekam medis ' . $transfusion->patient->medrec,
            };

            // ---------- Insert Log Activity ----------
            BloodTransfusionLogActivity::create([
                'blood_transfusion_public_id' => $transfusion->public_id,
                'payload' => $transfusion,
                'status' => BloodTransfusionLogActivityStatus::REGISTERED,
                'description' => generateBloodTransfusionLogDescription(
                    BloodTransfusionLogActivityStatus::REGISTERED,
                    $description,
                    Auth::user()->username
                ),
                'created_by_user_name' => Auth::user()->name,
                'timestamp' => now(),
            ]);

            DB::commit();

            globalLogger('info', 'New blood transfusion request inserted succesfully!', [
                'id' => $transfusion->id,
                'payload' => $transfusion,
            ], 200, 'newbloodtransfusion');
            return [
                'success' => true,
                'code' => 201,
                'data' => ['message' => 'Transaksi permintaan darah berhasil dibuat', 'public_id' => $transfusion->public_id, 'patient_name' => $patient->name,]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            globalLogger('error', 'New blood transfusion request failed to insert!', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
            ], 500, 'newbloodtransfusion');
            return [
                'success' => false,
                'code' => 500,
                'data' => ['message' => 'Transaksi permintaan darah gagal dibuat', 'error' => $e->getMessage(),]
            ];
        }
    }

    // ---------- Insert Reaction Transfusion ----------
    public function addReactionTransfusion(Request $request, string $detailPublicId): void
    {
        DB::beginTransaction();
        try {
            $detail = BloodTransfusionDetail::with([
                'bloodTransfusion',
                'bloodTransfusion.patient',
                'bloodStock',
            ])->where('public_id', $detailPublicId)->firstOrFail();

            $transfusionAt = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->transfusion_at,
            );

            $detail->update([
                'transfusion_result' => $request->transfusion_result,
                'transfusion_at' => $transfusionAt ?? now(),
            ]);

            $bagNumberReaction = $detail->bloodStock?->bag_number ?? '-';
            $description = match (true) {
                !empty($detail->bloodTransfusion?->order_number) => 'dengan nomor order ' . $detail->bloodTransfusion?->order_number,
                !empty($detail->bloodTransfusion?->lab_number) => 'dengan nomor BDRS ' . $detail->bloodTransfusion?->lab_number,
                default => 'dengan nomor rekam medis ' . $detail->bloodTransfusion?->patient->medrec,
            };

            BloodTransfusionLogActivity::create([
                'blood_transfusion_public_id' => $detail->bloodTransfusion?->public_id,
                'payload' => $detail,
                'status' => BloodTransfusionLogActivityStatus::REACTION_TRANSFUSION,
                'description' => generateBloodTransfusionLogDescription(
                    BloodTransfusionLogActivityStatus::REACTION_TRANSFUSION,
                    $description,
                    Auth::user()->username,
                    $bagNumberReaction
                ),
                'created_by_user_name' => Auth::user()->name,
                'timestamp' => now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
