<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Integer implements ValidationRule
{
    public function __construct(
        protected ?int $max = 2147483647, // Batas kolom INT (4-byte signed) pada umumnya
        protected ?int $min = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_int($value) && filter_var($value, FILTER_VALIDATE_INT) === false) {
            $fail(':attribute harus berupa bilangan bulat.');
            return;
        }

        $intValue = (int) $value;

        if ($this->min !== null && $intValue < $this->min) {
            $fail(":attribute tidak boleh kurang dari {$this->min}.");
            return;
        }

        if ($this->max !== null && $intValue > $this->max) {
            $fail(":attribute tidak boleh lebih dari {$this->max}.");
        }
    }
}
