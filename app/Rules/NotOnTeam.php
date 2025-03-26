<?php

namespace App\Rules;

use App\Models\Organization;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotOnTeam implements ValidationRule
{
    public function __construct(protected int $organizationId) {}

    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public bool $implicit = true;

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Organization::find($this->organizationId)->users()->where('email', $value)->exists()) {
            $fail("$value is already part of yor team");
        }
    }
}
