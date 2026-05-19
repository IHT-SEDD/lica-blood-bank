<?php

namespace App\Http\Requests\BloodTransfusion;

use App\Models\BloodTransfusion;
use App\Models\Insurance;
use App\Models\Room;
use App\Models\Doctor;
use Faker\Core\Blood;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBloodPacksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blood_packs.*' => 'required|array|min:1',
            'blood_packs.*.public_id' => 'nullable|string',
            'blood_packs.*.component_id' => 'required|string',
            'blood_packs.*.component_text' => 'required|string',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'blood_packs.required' => 'kantong darah harus diisi',
            'blood_packs.array'    => 'kantong darah harus berupa array',
            'blood_packs.min'      => ' harus ada minimal 1 kantong darah',
            'blood_packs.*.public_id.string' => 'public_id harus berupa string',
            'blood_packs.*.component_id.required' => 'component_id harus diisi',
            'blood_packs.*.component_id.string' => 'component_id harus berupa string',
            'blood_packs.*.component_text.required' => 'component_text harus diisi',
            'blood_packs.*.component_text.string' => 'component_text harus berupa string',
        ];
    }
}
