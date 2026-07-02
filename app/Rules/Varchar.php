<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Varchar implements ValidationRule
{
    public function __construct(
        protected int $max = 255, // Batas umum kolom VARCHAR
        protected ?int $min = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail(':attribute harus berupa teks.');
            return;
        }

        $length = mb_strlen($value);

        if ($this->min !== null && $length < $this->min) {
            $fail(":attribute minimal harus {$this->min} karakter.");
            return;
        }

        if ($length > $this->max) {
            $fail(":attribute tidak boleh lebih dari {$this->max} karakter.");
        }
    }
}
