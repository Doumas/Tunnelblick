<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class DJValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        // Name muss vorhanden und ein String sein, mindestens 3 Zeichen lang
        if (!v::stringType()->notEmpty()->length(3, null)->validate($data['name'] ?? null)) {
            $errors['name'] = 'Name is required, must be a string and at least 3 characters long.';
        }

        // Genre muss vorhanden und ein String sein
        if (!v::stringType()->notEmpty()->validate($data['genre'] ?? null)) {
            $errors['genre'] = 'Genre is required and must be a string.';
        }

        // Alter muss optional, aber eine Ganzzahl und mindestens 18 Jahre alt sein
        if (isset($data['age']) && !v::optional(v::intVal()->min(18))->validate($data['age'])) {
            $errors['age'] = 'Age must be an integer and at least 18 years old.';
        }

        // Erfahrung muss optional, aber eine Ganzzahl und positiv sein
        if (isset($data['experience']) && !v::optional(v::intVal()->min(0))->validate($data['experience'])) {
            $errors['experience'] = 'Experience must be a positive integer.';
        }

        return $errors;
    }
}
