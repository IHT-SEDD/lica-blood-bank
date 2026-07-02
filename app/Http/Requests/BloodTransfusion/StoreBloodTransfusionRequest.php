<?php

namespace App\Http\Requests\BloodTransfusion;

use App\Models\Patient;
use App\Models\Insurance;
use App\Models\Room;
use App\Models\Doctor;
use App\Rules\Date;
use App\Rules\Email;
use App\Rules\Exists;
use App\Rules\Integer;
use App\Rules\Json;
use App\Rules\OneOf;
use App\Rules\Varchar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBloodTransfusionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'gender'                     => 'jenis kelamin',
            'birthdate'                  => 'tanggal lahir',
            'patient_id'                 => 'pasien',
            'name'                       => 'nama pasien',
            'medrec'                     => 'nomor rekam medis',
            'email'                      => 'email',
            'phone_number'               => 'nomor telepon',
            'blood_group'                => 'golongan darah',
            'blood_rhesus'               => 'rhesus darah',
            'address'                    => 'alamat',
            'relation_name'              => 'nama kerabat',
            'relation_type'              => 'hubungan kerabat',
            'insurance_id'               => 'penjamin/insurance',
            'room_id'                    => 'ruangan',
            'doctor_id'                  => 'dokter',
            'diagnosis'                  => 'diagnosis',
            'blood_required_at'          => 'tanggal kebutuhan darah',
            'selected_blood_components'  => 'komponen darah',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * Mengubah public_id (yang dikirim dari frontend) menjadi internal id
     * sebelum divalidasi. Jika public_id diberikan tapi tidak ditemukan,
     * gunakan sentinel value (-1) — bukan null — agar rule Exists tetap
     * menangkapnya sebagai "tidak valid", bukan dianggap "tidak diisi".
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'patient_id' => $this->resolveInternalId(Patient::class, $this->input('patient_id')),
            'insurance_id' => $this->resolveInternalId(Insurance::class, $this->input('insurance_id')),
            'room_id' => $this->resolveInternalId(Room::class, $this->input('room_id')),
            'doctor_id' => $this->resolveInternalId(Doctor::class, $this->input('doctor_id')),
        ]);
    }

    /**
     * Resolve public_id menjadi internal id dari sebuah Model.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     */
    private function resolveInternalId(string $modelClass, mixed $publicId): ?int
    {
        if ($publicId === null || $publicId === '') {
            return null;
        }

        $id = $modelClass::where('public_id', $publicId)->value('id');

        // -1 dipakai sebagai sentinel: public_id diberikan tapi tidak ditemukan.
        // Ini penting agar tidak tertukar dengan "field memang tidak diisi" (null).
        return $id !== null ? (int) $id : -1;
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
            'patient_id'    => ['nullable', new Exists(Patient::class, message: 'Pasien yang dipilih tidak ditemukan.')],
            'name'          => [Rule::requiredIf($isNewPatient), new Varchar(min: 5)],
            'gender'        => [Rule::requiredIf($isNewPatient), new OneOf(['M', 'F'])],
            'birthdate'     => [Rule::requiredIf($isNewPatient), new Date],
            'medrec'        => ['nullable', new Varchar(max: 50, min: 10)],
            'email'         => ['nullable', new Email],
            'phone_number'  => ['nullable', new Varchar(max: 20, min: 15)],
            'blood_group'   => ['nullable', new Varchar(max: 10)],
            'blood_rhesus'  => ['nullable', new Varchar(max: 10)],
            'address'       => ['nullable', new Varchar(min: 5)],
            'relation_name' => ['nullable', new Varchar(min: 5)],
            'relation_type' => ['nullable', new Varchar(min: 5, max: 100)],

            // ---------- Transaction ----------
            'insurance_id' => ['nullable', new Exists(Insurance::class, message: 'Penjamin/Insurance yang dipilih tidak ditemukan.')],
            'room_id'      => ['nullable', new Exists(Room::class, message: 'Ruangan yang dipilih tidak ditemukan.')],
            'doctor_id'    => ['nullable', new Exists(Doctor::class, message: 'Dokter yang dipilih tidak ditemukan.')],
            'diagnosis'    => ['nullable', new Varchar(min: 5)],

            // ---------- Blood Request ----------
            'blood_required_at'         => ['required', new Date],
            'selected_blood_components' => ['required', new Json],
        ];
    }

    /**
     * Decode selected_blood_components JSON into an array after validation passes.
     */
    public function selectedComponents(): array
    {
        return json_decode($this->input('selected_blood_components', '[]'), true) ?? [];
    }
}
