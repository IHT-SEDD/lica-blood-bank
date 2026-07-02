<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Json implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail(":attribute harus berupa JSON yang valid.");
            return;
        }

        if (function_exists('json_validate')) {
            $valid = json_validate($value);
        } else {
            json_decode($value);
            $valid = json_last_error() === JSON_ERROR_NONE;
        }

        if (!$valid) {
            $fail("{$attribute} harus berupa JSON yang valid.");
        }
    }
}
