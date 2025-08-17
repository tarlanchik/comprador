<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TrixContentRequired implements ValidationRule
{
    /**
     * Indicate that the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true; // Добавьте эту строку

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Логирование для отладки
       // \Log::info("cleanValue for {$attribute}: [{$value}]");

        $cleanContent = trim(strip_tags((string) $value));

        if (empty($cleanContent)) {
            $fail('Поле обязательно для заполнения.');
        }
    }
}
