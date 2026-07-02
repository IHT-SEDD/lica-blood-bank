<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OneOf implements ValidationRule
{
    /**
     * @param  array<int, string|int>  $options  Daftar nilai yang diperbolehkan, contoh: ['M', 'F']
     * @param  bool  $strict  Gunakan perbandingan strict (tipe data harus sama persis)
     */
    public function __construct(
        protected array $options,
        protected bool $strict = true,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, $this->options, $this->strict)) {
            $list = implode(', ', $this->options);
            $fail(":attribute harus salah satu dari: {$list}.");
        }
    }
}
