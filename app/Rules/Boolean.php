<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Boolean implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $acceptable = [true, false, 0, 1, '0', '1'];

        if (!in_array($value, $acceptable, true)) {
            $fail(':attribute harus berupa nilai boolean.');
        }
    }
}
