<?php

namespace App\Validator;

use Respect\Validation\Validator as v;

class MediaValidator
{
    public static function rules(): array
    {
        return [
            'file' => v::optional(v::file())
        ];
    }
}
