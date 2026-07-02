<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Exists implements ValidationRule
{
    /**
     * @param  class-string<Model>  $model  Contoh: Patient::class
     * @param  string  $column  Kolom yang dicocokkan dengan value, default 'id'
     * @param  (Closure(Builder): void)|null  $constraint  Callback opsional untuk kondisi tambahan (soft delete, scope, dll)
     */
    public function __construct(
        protected string $model,
        protected string $column = 'id',
        protected ?Closure $constraint = null,
        protected ?string $message = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value) || $value === '') {
            $fail(':attribute tidak boleh kosong.');
            return;
        }

        /** @var Model $instance */
        $instance = new $this->model;

        $query = $instance->newQuery()->where($this->column, $value);

        if ($this->constraint) {
            ($this->constraint)($query);
        }

        if (!$query->exists()) {
            $fail($this->message ?? ':attribute tidak valid atau tidak ditemukan.');
        }
    }
}
