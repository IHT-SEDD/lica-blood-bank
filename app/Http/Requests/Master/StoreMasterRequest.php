<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreMasterRequest extends FormRequest
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
            'name.required' => 'Name is Required',
            'username.required' => 'Username is Required',
            'username.unique' => 'Username is already use',
            'password.required' => 'Password is Required',
            'password.min' => 'Password minimal 5 characters',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $master = $this->route('master');

        $modelClass = config("master.$master.model");

        if (!$modelClass || !method_exists($modelClass, 'rules')) {
            return [];
        }

        return $modelClass::rules();
    }
}
