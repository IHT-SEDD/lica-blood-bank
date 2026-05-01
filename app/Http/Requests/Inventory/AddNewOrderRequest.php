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

    public function messages()
    {
        return [
            'vendor_id.required' => 'Vendor is Required',
            'vendor_id.exists' => 'Vendor not found',
            'po_number.required' => 'PO number is Required',
            'po_number.unique' => 'PO number is already exist',
            'blood_data.required' => 'Blood data is required',
            'blood_data.array' => 'Blood data must be an array',
            'blood_data.min' => 'Blood data must have at least 1 item',
            'blood_data.*.blood_group.required' => 'Blood group is required',
            'blood_data.*.blood_rhesus.required' => 'Blood rhesus is required',
            'blood_data.*.blood_rhesus.in' => 'Blood rhesus must be + or -',
            'blood_data.*.blood_component.required' => 'Blood component is required',
            'blood_data.*.blood_quantity.required' => 'Blood quantity is required',
            'blood_data.*.blood_quantity.integer' => 'Blood quantity must be a number',
            'blood_data.*.blood_quantity.min' => 'Blood quantity must be at least 1',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // ---------- Validasi field statis :begin ----------
            'vendor_id' => 'required|exists:vendors,public_id',
            'po_number' => 'required|unique:order_bloods,po_number',
            // ---------- Validasi field statis :end ----------

            // ---------- Validasi blood data array :begin ----------
            'blood_data' => 'required|array|min:1',
            'blood_data.*.blood_group' => ['required', new Enum(BloodGroup::class)],
            'blood_data.*.blood_rhesus' => 'required|in:+,-',
            'blood_data.*.blood_component' => ['required', new Enum(BloodComponent::class)],
            'blood_data.*.blood_quantity' => 'required|integer|min:1',
            // ---------- Validasi blood data array :end ----------
        ];
    }
}
