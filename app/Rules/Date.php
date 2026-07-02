<?php

namespace App\Rules;

use Closure;
use DateTime;
use Illuminate\Contracts\Validation\ValidationRule;

class Date implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail(":attribute harus berupa tanggal yang valid.");
            return;
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);

        if (!$date || $date->format('Y-m-d') !== $value) {
            $fail(":attribute harus berupa tanggal yang valid, dengan format YYYY-MM-DD.");
        }
    }
}
