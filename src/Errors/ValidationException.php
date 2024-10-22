<?php

namespace App\Errors;

use Exception;

class ValidationException extends Exception
{
    protected array $errors;

    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors; // Setze die Fehler
    }

    public function getErrors(): array
    {
        return $this->errors; // Stelle sicher, dass dies ein Array zurückgibt
    }
}
