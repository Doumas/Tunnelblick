<?php

namespace App\Controllers;

use App\Models\DJ;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class DJController
{
    // Hilfsmethode zur Vereinfachung der JSON-Antworten
    private function jsonResponse(Response $response, $data, int $status = 200): Response
    {
        $response->getBody()->write(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    // Index-Methode für GET /dj
    public function index(Request $request, Response $response): Response
    {
        $djs = DJ::all(); // Alle DJs abrufen
        return $this->jsonResponse($response, $djs);
    }

    // Show-Methode für GET /dj/{id}
    public function show(Request $request, Response $response, array $args): Response
    {
        $dj = DJ::find($args['id']);
        if ($dj) {
            return $this->jsonResponse($response, $dj);
        } else {
            return $this->jsonResponse($response, ['error' => 'DJ not found'], 404);
        }
    }

    // Store-Methode für POST /dj
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Validierung der Eingabedaten
        $errors = $this->validateDJData($data);
        if (!empty($errors)) {
            return $this->jsonResponse($response, ['errors' => $errors], 400);
        }

        $dj = DJ::create($data);
        return $this->jsonResponse($response, $dj, 201);
    }

    // Update-Methode für PUT /dj/{id}
    public function update(Request $request, Response $response, array $args): Response
    {
        $dj = DJ::find($args['id']);
        if (!$dj) {
            return $this->jsonResponse($response, ['error' => 'DJ not found'], 404);
        }

        $data = $request->getParsedBody();

        // Validierung der Eingabedaten
        $errors = $this->validateDJData($data);
        if (!empty($errors)) {
            return $this->jsonResponse($response, ['errors' => $errors], 400);
        }

        $dj->update($data);
        return $this->jsonResponse($response, $dj);
    }

    // Destroy-Methode für DELETE /dj/{id}
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $dj = DJ::find($args['id']);
        if (!$dj) {
            return $this->jsonResponse($response, ['error' => 'DJ not found'], 404);
        }

        $dj->delete();
        return $this->jsonResponse($response, ['message' => 'DJ deleted successfully']);
    }

    // Validierungsmethode für DJ-Daten mit Respect\Validation
    private function validateDJData(array $data): array
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
