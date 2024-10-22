<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    // Methode zur Behandlung der Homepage-Anfrage
    public function index(Request $request, Response $response): Response
    {
        // Beispielinhalt, der als JSON zurückgegeben wird
        $data = [
            'message' => 'Welcome to the Home Page!',
            'status' => 'success'
        ];

        // JSON-Antwort zurückgeben
        $response->getBody()->write(json_encode($data));

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }
}
