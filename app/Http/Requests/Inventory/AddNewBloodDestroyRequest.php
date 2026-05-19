<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class AddNewBloodDestroyRequest extends FormRequest
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
        $baseRules = [
            'reason' => ['required', 'string'],
            'method_add' => ['required', 'string', 'in:manual,scan'],
        ];

        if ($this->input('method_add') === 'manual') {
            $manualRules = [
                'bag_numbers' => ['required', 'array', 'min:1'],
                'bag_numbers.*' => ['required', 'string', 'exists:blood_stocks,bag_number'],
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
            'reason.required' => 'Purchase Order wajib diisi.',

            'method_add.required' => 'Method wajib dipilih.',
            'method_add.in' => 'Method harus manual atau scan.',

            'bag_numbers.required' => 'Minimal 1 nomor kantong darah wajib dipilih.',
            'bag_numbers.min' => 'Minimal 1 nomor kantong darah wajib dipilih.',
            'bag_numbers.exists' => 'Kantong darah yang dipilih tidak tersedia.',
        ];
    }
}
