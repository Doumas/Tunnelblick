<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AdminMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Sicherstellen, dass die Sitzung gestartet ist
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $response = new \Slim\Psr7\Response();
            return $response->withStatus(403)
                            ->withHeader('Content-Type', 'application/json')
                            ->getBody()->write(json_encode(['error' => 'Access denied']));
        }

        return $handler->handle($request);
    }
}
