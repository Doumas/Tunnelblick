<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class BlogPostValidator
{
    public static function getValidationRules(): array
    {
        return [
            'title' => v::stringType()->length(1, 255),
            'main_image' => v::optional(v::url()->length(1, 255)),
            'content' => v::stringType()->notEmpty(),
            'dj_id' => v::optional(v::intVal())
        ];
    }
}
