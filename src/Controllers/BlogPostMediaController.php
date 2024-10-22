<?php

namespace App\Controllers;

use App\Models\BlogPostMedia;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class BlogPostMediaController
{
    // Alle Medien anzeigen
    public function index(Request $request, Response $response): Response
    {
        $media = BlogPostMedia::all();
        return $this->respondWithJson($response, $media);
    }

    // Ein einzelnes Medium anzeigen
    public function show(Request $request, Response $response, array $args): Response
    {
        $media = BlogPostMedia::find($args['id']);

        if (!$media) {
            return $this->respondWithJson($response, ['error' => 'Media not found'], 404);
        }

        return $this->respondWithJson($response, $media);
    }

    // Neues Medium erstellen
    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Validierung
        $validationErrors = $this->validate($data);
        if ($validationErrors) {
            return $this->respondWithJson($response, ['errors' => $validationErrors], 422);
        }

        $media = BlogPostMedia::create($data);
        return $this->respondWithJson($response, $media, 201);
    }

    // Ein Medium aktualisieren
    public function update(Request $request, Response $response, array $args): Response
    {
        $media = BlogPostMedia::find($args['id']);

        if (!$media) {
            return $this->respondWithJson($response, ['error' => 'Media not found'], 404);
        }

        $data = $request->getParsedBody();

        // Validierung
        $validationErrors = $this->validate($data);
        if ($validationErrors) {
            return $this->respondWithJson($response, ['errors' => $validationErrors], 422);
        }

        $media->update($data);
        return $this->respondWithJson($response, $media);
    }

    // Ein Medium löschen
    public function delete(Request $request, Response $response, array $args): Response
    {
        $media = BlogPostMedia::find($args['id']);

        if (!$media) {
            return $this->respondWithJson($response, ['error' => 'Media not found'], 404);
        }

        $media->delete();
        return $response->withStatus(204); // No Content
    }

    // Validierungsmethode für die Mediendaten
    protected function validate(array $data)
    {
        $validator = v::key('media_url', v::url()->length(1, 255))
            ->key('media_type', v::in(['image', 'video', 'audio', 'other']))
            ->key('caption', v::optional(v::stringType()))
            ->key('position', v::optional(v::intVal()));

        try {
            $validator->assert($data);
            return null;  // Keine Fehler
        } catch (ValidationException $e) {
            return $e->getMessage();  // Alle Fehlermeldungen zurückgeben
        }
    }

    // Einheitliche JSON-Antwortmethode
    protected function respondWithJson(Response $response, $data, int $status = 200): Response
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }
}
