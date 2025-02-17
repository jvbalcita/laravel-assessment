<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotSelectOne implements ValidationRule
{
    private $isRequired;

    public function __construct($isRequired = false)
    {
        $this->isRequired = $isRequired;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isRequired && $value === null || trim($value) === '' || $value === 'Select One') {
            /*$fail('The :attribute field cannot be empty.');*/
            $fail('This field is required.');
        }
    }
}
