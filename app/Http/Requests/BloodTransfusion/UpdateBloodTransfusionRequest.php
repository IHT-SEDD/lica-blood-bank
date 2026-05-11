<?php

namespace App\Http\Requests\BloodTransfusion;

use App\Models\BloodTransfusion;
use App\Models\Insurance;
use App\Models\Room;
use App\Models\Doctor;
use Faker\Core\Blood;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBloodTransfusionRequest extends FormRequest
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

        $publicId = $this->id !== null ?  $this->id : null;
        if ($publicId) {
            $blood_transfusion = BloodTransfusion::where('public_id', $publicId)->first();
            if ($blood_transfusion) {
                $blood_transfusion_id = $blood_transfusion->id;
            }
        }

        $insurancePublicId = $this->insurance_id !== null ?  $this->insurance_id : null;
        if ($insurancePublicId) {
            $insurance = Insurance::where('public_id', $insurancePublicId)->first();
            if ($insurance) {
                $insurance_id = $insurance->id;
            }
        }

        $roomPublicId = $this->room_id !== null ?  $this->room_id : null;
        if ($roomPublicId) {
            $room = Room::where('public_id', $roomPublicId)->first();
            if ($room) {
                $room_id = $room->id;
            }
        }

        $doctorPublicId = $this->doctor_id !== null ?  $this->doctor_id : null;
        if ($doctorPublicId) {
            $doctor = Doctor::where('public_id', $doctorPublicId)->first();
            if ($doctor) {
                $doctor_id = $doctor->id;
            }
        }

        $this->merge([
            'id' => isset($blood_transfusion_id) ? (int) $blood_transfusion_id : $this->id,
            'insurance_id' => isset($insurance_id) ? (int) $insurance_id : $this->insurance_id,
            'room_id'      => isset($room_id) ? (int) $room_id : $this->room_id,
            'doctor_id'    => isset($doctor_id) ? (int) $doctor_id : $this->doctor_id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Patient details update
            'blood_group'          => ['nullable', 'string', 'max:10'],
            'blood_rhesus'         => ['nullable', 'string', 'max:5'],
            'relation_name'        => ['nullable', 'string', 'max:255'],
            'relation_type'        => ['nullable', 'string', 'max:100'],

            // Transaction
            'id'                   => ['required', 'exists:blood_transfusions,id'],
            'insurance_id'         => ['required', 'exists:insurances,id'],
            'room_id'              => ['required', 'exists:rooms,id'],
            'doctor_id'            => ['required', 'exists:doctors,id'],
            'diagnosis'            => ['nullable', 'string'],

            // Blood Request
            'blood_required_at'    => ['required', 'date'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            // Transaction
            'id.required'               => 'ID Transaksi wajib diisi.',
            'id.exists'                 => 'ID Transaksi tidak valid.',
            'insurance_id.required'     => 'Asuransi wajib dipilih.',
            'insurance_id.exists'       => 'Asuransi yang dipilih tidak valid.',
            'room_id.required'          => 'Ruangan wajib dipilih.',
            'room_id.exists'            => 'Ruangan yang dipilih tidak valid.',
            'doctor_id.required'         => 'Dokter wajib dipilih.',
            'doctor_id.exists'           => 'Dokter yang dipilih tidak valid.',

            // Blood Request
            'blood_required_at.required' => 'Tanggal kebutuhan darah wajib diisi.',
            'blood_required_at.date'     => 'Format tanggal kebutuhan darah tidak valid.',
        ];
    }
}
