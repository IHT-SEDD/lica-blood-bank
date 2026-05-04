<?php

namespace App\Http\Requests\Inventory;

use App\Enums\BloodComponent;
use App\Enums\BloodGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AddNewOrderRequest extends FormRequest
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
        // Validasi field yang selalu wajib ada terlepas dari method
        $baseRules = [
            'po_number' => ['required', 'string', 'unique:order_bloods,po_number'],
            'vendor_id' => ['required', 'string', 'exists:vendors,public_id'],
        ];

        // Validasi tiap item blood_data wajib diisi
        $manualRules = [
            'blood_data' => ['required', 'array', 'min:1'],
            'blood_data.*.blood_pack_id' => ['required', 'string', 'exists:blood_packs,public_id'],
            'blood_data.*.quantity' => ['required', 'integer', 'min:1'],
        ];

        return array_merge($baseRules, $manualRules);
    }

    public function messages(): array
    {
        return [
            'po_number.required' => 'Purchase Order wajib dipilih.',
            'po_number.exists' => 'Purchase Order yang dipilih tidak valid.',
            'blood_data.required' => 'Minimal 1 data darah wajib diisi.',
            'blood_data.min' => 'Minimal 1 data darah wajib diisi.',
            'blood_data.*.blood_pack_id.required' => 'Blood pack wajib dipilih.',
            'blood_data.*.quantity.required' => 'Quantity wajib diisi.',
        ];
    }
}
