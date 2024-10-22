<?php

namespace App\Middleware;

use Slim\Csrf\Guard;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CsrfMiddleware
{
    protected $csrf;

    public function __construct(Guard $csrf)
    {
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        // CSRF Token in die Antwort einfügen (z.B. als HTML-Meta-Tags)
        $csrfNameKey = $this->csrf->getTokenNameKey();
        $csrfValueKey = $this->csrf->getTokenValueKey();
        $csrfName = $this->csrf->getTokenName();
        $csrfValue = $this->csrf->getTokenValue();

        $csrfMetaTags = sprintf(
            '<meta name="csrf-name-key" content="%s"><meta name="csrf-value-key" content="%s"><meta name="csrf-name" content="%s"><meta name="csrf-value" content="%s">',
            $csrfNameKey, $csrfValueKey, $csrfName, $csrfValue
        );

        $response->getBody()->write($csrfMetaTags);

        return $response;
    }
}
