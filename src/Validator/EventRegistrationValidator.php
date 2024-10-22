<?php

namespace App\Validators;

use App\Errors\ValidationException;

class EventRegistrationValidator
{
    public static function validate(array $data)
    {
        $errors = [];

        // Überprüfe auf fehlende Felder
        if (empty($data['event_id'])) {
            $errors['event_id'] = 'Event ID is required.';
        }
        if (empty($data['user_id'])) {
            $errors['user_id'] = 'User ID is required.';
        }
        if (empty($data['ticket_type'])) {
            $errors['ticket_type'] = 'Ticket type is required.';
        }
        if (!isset($data['price'])) {
            $errors['price'] = 'Price is required.';
        } elseif (!is_numeric($data['price'])) {
            $errors['price'] = 'Price must be a number.';
        }
        if (!isset($data['payment_status'])) {
            $errors['payment_status'] = 'Payment status is required.';
        } elseif (!in_array($data['payment_status'], ['pending', 'paid', 'cancelled'])) {
            $errors['payment_status'] = 'Payment status must be pending, paid, or cancelled.';
        }

        // Wenn es Fehler gibt, werfe eine ValidationException
        if (!empty($errors)) {
            throw new ValidationException('Validation failed', $errors);
        }
    }
}
