<?php

namespace App\Http\Requests\Playground;

use Illuminate\Foundation\Http\FormRequest;

class EditCrossmatchResult extends FormRequest
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
            'mayor_result' => ['nullable', 'string'],
            'minor_result' => ['nullable', 'string'],
            'auto_control_result' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mayor_result.string' => 'Hasil mayor wajib string.',
            'minor_result.string' => 'Hasil minor wajib string.',
            'auto_control_result.string' => 'Hasil auto control wajib string.',
        ];
    }
}
