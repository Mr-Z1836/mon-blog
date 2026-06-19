<?php

namespace App\Rules;

use App\Support\ForbiddenUsernames;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedUsername implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if (ForbiddenUsernames::isForbidden($value)) {
            $fail('Ce pseudo est interdit ou réservé. Choisis un autre nom.');
        }
    }
}
