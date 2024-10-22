<?php

namespace App\Validator;

use Respect\Validation\Validator as v;

class DonationValidator
{
    public static function rules(): array
    {
        return [
            'user_id' => v::intVal()->notEmpty(),
            'amount' => v::floatVal()->min(1),
            'message' => v::optional(v::stringType())
        ];
    }
}
