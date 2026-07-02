<?php

namespace App\Rules;

use Closure;
use DateTime as GlobalDateTime;
use Illuminate\Contracts\Validation\ValidationRule;

class DateTime implements ValidationRule
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

        $dateTime = GlobalDateTime::createFromFormat('Y-m-d H:i:s', $value);

        if (!$dateTime || $dateTime->format('Y-m-d H:i:s') !== $value) {
            $fail(":attribute harus berupa tanggal yang valid, dengan format YYYY-MM-DD HH:MM:SS.");
        }
    }
}
