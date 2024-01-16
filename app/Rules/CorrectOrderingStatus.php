<?php

namespace App\Rules;

use App\Enums\BaseItemOrderingStatus;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CorrectOrderingStatus implements DataAwareRule, ValidationRule
{

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data['data'];

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            in_array($this->data['storehouse'], [null, 0])
            && $value == BaseItemOrderingStatus::NONE->value
        ) {
            $fail('validation.other_than_in_stock')->translate(); //The :attribute must be specified other than "in stock".
        } elseif ($this->data['storehouse'] > 0 && $value != BaseItemOrderingStatus::NONE->value) {
            $fail('validation.should_be_in_stock')->translate(); //The :attribute should be ("in stock").
        }
    }
}
