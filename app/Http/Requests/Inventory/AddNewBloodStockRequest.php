<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class AddNewBloodStockRequest extends FormRequest
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
            'po_number' => ['required', 'string', 'exists:order_bloods,po_number'],
            'method_add' => ['required', 'string', 'in:manual,scan'],
        ];

        if ($this->input('method_add') === 'manual') {
            // Validasi untuk method manual — tiap item blood_data wajib diisi
            $manualRules = [
                'bag_numbers' => ['required', 'array', 'min:1'],
                'bag_numbers.*' => ['required', 'string', 'exists:incoming_blood_details,public_id'],
            ];

            return array_merge($baseRules, $manualRules);
        }

        // Validasi untuk method scan
        $scanRules = [
            'bag_numbers' => ['required', 'string'],
        ];

        return array_merge($baseRules, $scanRules);
    }

    public function messages(): array
    {
        return [
            'po_number.required' => 'Purchase Order wajib dipilih.',
            'po_number.exists' => 'Purchase Order yang dipilih tidak valid.',

            'method_add.required' => 'Method wajib dipilih.',
            'method_add.in' => 'Method harus manual atau scan.',

            'bag_numbers.required' => 'Minimal 1 nomor kantong darah wajib dipilih.',
            'bag_numbers.min' => 'Minimal 1 nomor kantong darah wajib dipilih.',
            'bag_numbers.exists' => 'Kantong darah yang dipilih tidak tersedia.',
        ];
    }
}
