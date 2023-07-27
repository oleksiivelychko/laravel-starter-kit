<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class CreditCard implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if ($this->isValidLuhn($value)) {
            $fail('The credit card [:attribute] has not passed Luhn validation.');
        }
    }

    /**
     * Luhn algorithm number checker.
     * https://gist.github.com/troelskn/1287893
     * https://en.wikipedia.org/wiki/Luhn_algorithm.
     */
    private function isValidLuhn($number): bool
    {
        settype($number, 'string');

        $sumTable = [
            [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            [0, 2, 4, 6, 8, 1, 3, 5, 7, 9]];

        $sum = 0;
        $flip = 0;

        for ($i = strlen($number) - 1; $i >= 0; --$i) {
            $sum += $sumTable[$flip++ & 0x1][$number[$i]];
        }

        return 0 === $sum % 10;
    }
}
