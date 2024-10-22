<?php

namespace App\Validators;

use Respect\Validation\Validator as v;
use App\Errors\ValidationException;

class AdminValidator
{
    public static function validate(array $data): void
    {
        $errors = [];

        // Überprüfung auf Name
        if (!v::stringType()->notEmpty()->length(3, null)->validate($data['name'] ?? null)) {
            $errors['name'] = 'Name is required, must be a string and at least 3 characters long.';
        }

        // Überprüfung auf E-Mail
        if (!v::email()->validate($data['email'] ?? null)) {
            $errors['email'] = 'Valid email is required.';
        }

        // Überprüfung auf Passwort
        if (!v::stringType()->notEmpty()->length(6, null)->validate($data['password'] ?? null)) {
            $errors['password'] = 'Password is required and must be at least 6 characters long.';
        }

        // Fehler werfen, falls Validierungsfehler auftreten
        if (!empty($errors)) {
            throw new ValidationException('Validation failed', $errors);
        }
    }
}
