<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;
use Respect\Validation\Exceptions\NestedValidationException;

class ValidationMiddleware
{
    protected $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $data = $request->getParsedBody();
        $response = new SlimResponse();

        try {
            foreach ($this->rules as $field => $rule) {
                $rule->check($data[$field] ?? null);
            }
        } catch (NestedValidationException $e) {
            $errorData = [
                'error' => true,
                'message' => $e->getMessages()
            ];
            $response->getBody()->write(json_encode($errorData, JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }
}
