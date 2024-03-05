<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MobileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
    public function passes($attribute, $value)
    {
        $mobileRegex = '~^(0098|\+?98|0)9\d{9}$~';
        preg_match($mobileRegex, $value, $maches);
        return !empty($maches);
    }

    public function message()
    {
        return 'شماره موبایل وارد شده اشتباه میباشد';
    }
}
