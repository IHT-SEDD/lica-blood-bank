<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class EditBloodStockRequest extends FormRequest
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
            'volume' => ['required', 'int'],
            'status' => ['required', 'string'],
            'storage_rack_id' => ['nullable', 'string', 'exists:storage_racks,public_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'volume.required' => 'Volume wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'storage_rack_id.exists' => 'Storage rack tidak tersedia',
        ];
    }
}
