<?php

namespace App\Errors;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Throwable;

class ErrorHandler
{
    public function __invoke(Request $request, Response $response, Throwable $exception): Response
    {
        // Setze den Status auf 404 für HTTP-NotFoundExceptions, 400 für ValidationExceptions und 500 für andere
        if ($exception instanceof ValidationException) {
            $status = 400;
            $error = [
                'status' => 'validation_error',
                'errors' => $exception->getErrors() // Holen Sie sich die Validierungsfehler
            ];
        } elseif ($exception instanceof HttpNotFoundException) {
            $status = 404;
            $error = [
                'status' => 'error',
                'message' => 'Not found'
            ];
        } else {
            $status = 500;
            $error = [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
        }

        $payload = json_encode($error, JSON_PRETTY_PRINT);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }
}
