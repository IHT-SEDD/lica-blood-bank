<?php

namespace App\Http\Requests\BloodTransfusion;

use App\Models\Patient;
use App\Models\Insurance;
use App\Models\Room;
use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;

class StoreBloodTransfusionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {

        $patientPublicId = $this->patient_id !== null ?  $this->patient_id : null;
        if($patientPublicId){
            $patient_id = Patient::where('public_id', $patientPublicId)->first()->id;
        }
        $insurancePublicId = $this->insurance_id !== null ?  $this->insurance_id : null;
        if($insurancePublicId){
            $insurance_id = Insurance::where('public_id', $insurancePublicId)->first()->id;
        }

        $roomPublicId = $this->room_id !== null ?  $this->room_id : null;
        if($roomPublicId){
            $room_id = Room::where('public_id', $roomPublicId)->first()->id;
        }

        $doctorPublicId = $this->doctor_id !== null ?  $this->doctor_id : null;
        if($doctorPublicId){
            $doctor_id = Doctor::where('public_id', $doctorPublicId)->first()->id;
        }

        $this->merge([
            'patient_id'   => isset($patient_id) ? (int) $patient_id : null,
            'insurance_id' => isset($insurance_id) ? (int) $insurance_id : null,
            'room_id'      => isset($room_id) ? (int) $room_id : null,
            'doctor_id'    => isset($doctor_id) ? (int) $doctor_id : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isNewPatient = empty($this->input('patient_id'));

        return [
            // ---------- Patient ----------
            'patient_id'           => ['nullable', 'integer', 'exists:patients,id'],
            'name'                 => [$isNewPatient ? 'required' : 'nullable', 'string', 'max:255'],
            'gender'               => [$isNewPatient ? 'required' : 'nullable', 'in:M,F'],
            'birthdate'            => [$isNewPatient ? 'required' : 'nullable', 'date'],
            'medrec'               => ['nullable', 'string', 'max:50'],
            'email'                => ['nullable', 'email', 'max:255'],
            'phone_number'         => ['nullable', 'string', 'max:30'],
            'blood_group'          => ['nullable', 'string', 'max:10'],
            'blood_rhesus'         => ['nullable', 'string', 'max:5'],
            'address'              => ['nullable', 'string'],
            'relation_name'        => ['nullable', 'string', 'max:255'],
            'relation_type'        => ['nullable', 'string', 'max:100'],

            // ---------- Transaction ----------
            'insurance_id'         => ['nullable', 'exists:insurances,id'],
            'room_id'              => ['nullable', 'exists:rooms,id'],
            'doctor_id'            => ['nullable', 'exists:doctors,id'],
            'diagnosis'            => ['nullable', 'string'],

            // ---------- Blood Request ----------
            'blood_required_at'    => ['required', 'date'],
            'selected_blood_packs' => ['required', 'json'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            // Patient
            'patient_id.exists'          => 'Pasien yang dipilih tidak valid.',
            'name.required'              => 'Nama pasien wajib diisi.',
            'name.max'                   => 'Nama pasien maksimal 255 karakter.',
            'gender.required'            => 'Jenis kelamin wajib dipilih.',
            'gender.in'                  => 'Jenis kelamin harus M atau F.',
            'birthdate.required'         => 'Tanggal lahir wajib diisi.',
            'birthdate.date'             => 'Format tanggal lahir tidak valid.',
            'email.email'                => 'Format email tidak valid.',
            'phone_number.max'           => 'Nomor telepon maksimal 30 karakter.',

            // Transaction
            'insurance_id.exists'        => 'Asuransi yang dipilih tidak valid.',
            'room_id.exists'             => 'Ruangan yang dipilih tidak valid.',
            'doctor_id.exists'           => 'Dokter yang dipilih tidak valid.',

            // Blood Request
            'blood_required_at.required' => 'Tanggal kebutuhan darah wajib diisi.',
            'blood_required_at.date'     => 'Format tanggal kebutuhan darah tidak valid.',
            'selected_blood_packs.required' => 'Minimal satu blood pack harus dipilih.',
            'selected_blood_packs.json'  => 'Format data blood pack tidak valid.',
        ];
    }

    /**
     * Decode selected_blood_packs JSON into an array after validation passes.
     */
    public function selectedPacks(): array
    {
        return json_decode($this->input('selected_blood_packs', '[]'), true) ?? [];
    }
}
