<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class OrderNameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[A-Za-z\s]+$/', $value)) {
            $fail('Name contains non-English characters');
        }

        $words = explode(' ', $value);
        foreach ($words as $word) {
            if (empty($word)) {
                continue;
            }
            $firstChar = mb_substr($word, 0, 1);
            $upperCase = mb_strtoupper($firstChar);
            if ($firstChar !== $upperCase) {
                $fail('Name is not capitalized.');
                break;
            }
        }
    }
}
