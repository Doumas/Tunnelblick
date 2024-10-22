<?php

namespace App\Controllers;

use App\Models\EventRegistration; // Stellen Sie sicher, dass das Modell existiert
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\JsonResponseService;
use App\Validators\EventRegistrationValidator; 
use App\Errors\ValidationException;

class EventRegistrationController
{
    protected $jsonResponseService;

    public function __construct(JsonResponseService $jsonResponseService)
    {
        $this->jsonResponseService = $jsonResponseService;
    }

    public function index(Request $request, Response $response): Response
    {
        // Wandelt die Eloquent-Kollektion in ein Array um
        $registrations = EventRegistration::all()->toArray();
        return $this->jsonResponseService->success($response, $registrations);
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        try {
            EventRegistrationValidator::validate($data);
        } catch (ValidationException $e) {
            return $this->jsonResponseService->error($response, $e->getErrors(), 400);
        }

        $registration = EventRegistration::create($data);
        return $this->jsonResponseService->success($response, $registration, 201);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $registration = EventRegistration::find($args['id']);

        if (!$registration) {
            return $this->jsonResponseService->error($response, ['message' => 'Registration not found'], 404);
        }

        return $this->jsonResponseService->success($response, $registration);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $registration = EventRegistration::find($args['id']);

        if (!$registration) {
            return $this->jsonResponseService->error($response, ['message' => 'Registration not found'], 404);
        }

        $data = $request->getParsedBody();

        try {
            EventRegistrationValidator::validate($data);
        } catch (ValidationException $e) {
            return $this->jsonResponseService->error($response, $e->getErrors(), 400);
        }

        $registration->update($data);
        return $this->jsonResponseService->success($response, $registration);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $registration = EventRegistration::find($args['id']);

        if (!$registration) {
            return $this->jsonResponseService->error($response, ['message' => 'Registration not found'], 404);
        }

        $registration->delete();
        return $this->jsonResponseService->success($response, ['message' => 'Registration deleted successfully']);
    }
}
