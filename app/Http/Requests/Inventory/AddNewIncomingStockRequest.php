<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class AddNewIncomingStockRequest extends FormRequest
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
            'method_add' => ['required', 'string', 'in:manual,excel'],
            'batch_number' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->input('method_add') === 'manual') {
            // Validasi untuk method manual — tiap item blood_data wajib diisi
            $manualRules = [
                'blood_data' => ['required', 'array', 'min:1'],
                'blood_data.*.bag_number' => ['required', 'string', 'max:255'],
                'blood_data.*.blood_group' => ['required', 'string'],
                'blood_data.*.rhesus' => ['required', 'string'],
                'blood_data.*.blood_component' => ['required', 'string'],
                'blood_data.*.blood_volume' => ['required', 'numeric', 'min:1'],
                'blood_data.*.aftap_date' => ['required', 'date_format:d-m-Y'],
                'blood_data.*.expiry_date' => ['required', 'date_format:d-m-Y'],
                'blood_data.*.process_date' => ['required', 'date_format:d-m-Y'],
                'blood_data.*.is_hiv' => ['required', 'boolean'],
                'blood_data.*.is_hcv' => ['required', 'boolean'],
                'blood_data.*.is_hbsag' => ['required', 'boolean'],
                'blood_data.*.is_syphilis' => ['required', 'boolean'],
            ];

            return array_merge($baseRules, $manualRules);
        }

        // Validasi untuk method excel
        $excelRules = [
            'excel_file' => [
                'required',
                'file',
                // Validasi tipe file: xlsx, xls, csv
                'mimes:xlsx,xls,csv',
                // Maksimal 10MB
                'max:10240',
            ],
        ];

        return array_merge($baseRules, $excelRules);
    }

    public function messages(): array
    {
        return [
            'po_number.required' => 'Purchase Order wajib dipilih.',
            'po_number.exists' => 'Purchase Order yang dipilih tidak valid.',
            'method_add.required' => 'Method wajib dipilih.',
            'method_add.in' => 'Method harus manual atau excel.',
            'blood_data.required' => 'Minimal 1 data darah wajib diisi.',
            'blood_data.min' => 'Minimal 1 data darah wajib diisi.',
            'blood_data.*.bag_number.required' => 'Bag number wajib diisi.',
            'blood_data.*.blood_group.required' => 'Golongan darah wajib dipilih.',
            'blood_data.*.rhesus.required' => 'Rhesus wajib dipilih.',
            'blood_data.*.blood_component.required' => 'Komponen darah wajib dipilih.',
            'blood_data.*.blood_volume.required' => 'Volume wajib diisi.',
            'blood_data.*.blood_volume.numeric' => 'Volume harus berupa angka.',
            'blood_data.*.aftap_date.required' => 'Tanggal aftap wajib diisi.',
            'blood_data.*.aftap_date.date_format' => 'Format tanggal aftap harus dd-mm-yyyy.',
            'blood_data.*.expiry_date.required' => 'Tanggal expiry wajib diisi.',
            'blood_data.*.expiry_date.date_format' => 'Format tanggal expiry harus dd-mm-yyyy.',
            'blood_data.*.process_date.required' => 'Tanggal proses wajib diisi.',
            'blood_data.*.process_date.date_format' => 'Format tanggal proses harus dd-mm-yyyy.',
            'excel_file.required' => 'File excel wajib diupload.',
            'excel_file.mimes' => 'File harus berformat xlsx, xls, atau csv.',
            'excel_file.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
