<?php

namespace App\Controllers;

use App\Models\Donation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class DonationController
{
    public function index(Request $request, Response $response): Response
    {
        $donations = Donation::with('user', 'reward')->get();
        return $this->respondWithJson($response, $donations);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $donation = Donation::with('user', 'reward')->find($args['id']);
        if (!$donation) {
            return $this->respondWithJson($response, ['error' => 'Donation not found'], 404);
        }
        return $this->respondWithJson($response, $donation);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $validation = $this->validate($data);
        if ($validation !== true) {
            return $this->respondWithJson($response, ['errors' => $validation], 422);
        }

        $donation = Donation::create($data);
        return $this->respondWithJson($response, $donation, 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $donation = Donation::find($args['id']);
        if (!$donation) {
            return $this->respondWithJson($response, ['error' => 'Donation not found'], 404);
        }

        $data = $request->getParsedBody();

        $validation = $this->validate($data);
        if ($validation !== true) {
            return $this->respondWithJson($response, ['errors' => $validation], 422);
        }

        $donation->update($data);
        return $this->respondWithJson($response, $donation);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $donation = Donation::find($args['id']);
        if (!$donation) {
            return $this->respondWithJson($response, ['error' => 'Donation not found'], 404);
        }

        $donation->delete();
        return $response->withStatus(204); // No Content
    }

    protected function validate(array $data)
    {
        $validator = v::key('user_id', v::intVal()->notEmpty())
                      ->key('amount', v::numeric()->positive())
                      ->key('reward_id', v::optional(v::intVal()))
                      ->key('reward_sent', v::boolType())
                      ->key('payment_status', v::in(['pending', 'completed', 'failed']))
                      ->key('transaction_id', v::optional(v::stringType()->length(1, 255)))
                      ->key('shipment_tracking_number', v::optional(v::stringType()->length(1, 255)))
                      ->key('processing_status', v::in(['pending', 'processed', 'shipped']));

        try {
            $validator->assert($data);
            return true;
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            return $e->getMessage();
        }
    }

    protected function respondWithJson(Response $response, $data, int $status = 200): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }
}
