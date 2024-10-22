<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Validators\AdminValidator; // Angenommen, du hast auch eine Validierung ausgelagert
use App\Errors\ValidationException;
use App\Services\JsonResponseService; // Zentrale JSON-Antwortklasse

class AdminController
{
    protected JsonResponseService $jsonResponseService;

    public function __construct(JsonResponseService $jsonResponseService)
    {
        $this->jsonResponseService = $jsonResponseService;
    }

    // Dashboard-Methode (GET /admin)
    public function dashboard(Request $request, Response $response): Response
    {
        // Beispiel für Dashboard-Daten
        $data = [
            'users' => 150,
            'posts' => 45,
            'comments' => 120,
            'active_sessions' => 12
        ];

        return $this->jsonResponseService->success($response, ['dashboard' => $data]);
    }

    // Methode zum Erstellen eines neuen Admins (POST /admin/create)
    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Validierung der Eingabedaten mittels ausgelagertem AdminValidator
        try {
            AdminValidator::validate($data);
        } catch (ValidationException $e) {
            // Fehlerhafte Eingabedaten zurückgeben
            return $this->jsonResponseService->error($response, $e->getErrors(), 400);
        }

        // Hier würde ein neuer Admin erstellt werden (z.B. in der Datenbank)
        // $admin = Admin::create($data); // Beispiel für das Erstellen eines Admins

        return $this->jsonResponseService->success($response, ['message' => 'Admin created successfully'], 201);
    }
}
