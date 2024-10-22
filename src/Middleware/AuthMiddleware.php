<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Beispielhafte Authentifizierung (angepasst an deine Bedürfnisse)
        $authHeader = $request->getHeader('Authorization');
        if (empty($authHeader)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(401);
        }

        // Wenn authentifiziert, rufe die nächste Middleware oder den Controller auf
        return $handler->handle($request);
    }
}
