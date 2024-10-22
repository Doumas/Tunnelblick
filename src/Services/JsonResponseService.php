<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;

class JsonResponseService
{
    // Erfolgreiche Antwort mit Statuscode
    public function success(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    // Fehlerhafte Antwort mit Statuscode
    public function error(Response $response, array $errors, int $status = 400): Response
    {
        $errorResponse = [
            'status' => 'error',
            'errors' => $errors,
        ];

        $response->getBody()->write(
            json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
