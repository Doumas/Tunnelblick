<?php

namespace App\Validator;

use Respect\Validation\Validator as v;

class CommentValidator
{
    public static function rules(): array
    {
        return [
            'user_id' => v::intVal()->notEmpty(),
            'content' => v::stringType()->notEmpty(),
            'post_id' => v::optional(v::intVal()),
            'video_id' => v::optional(v::intVal())
        ];
    }
}
