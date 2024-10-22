<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\EventRegistrationController;
use App\Middleware\AuthMiddleware;

class EventRegistrationRoutes
{
    public static function register(App $app): void
    {
        $app->group('/event-registrations', function () use ($app) {
            $app->get('', [EventRegistrationController::class, 'index']);
            $app->post('', [EventRegistrationController::class, 'store']);
            $app->get('/{id}', [EventRegistrationController::class, 'show']);
            $app->put('/{id}', [EventRegistrationController::class, 'update']);
            $app->delete('/{id}', [EventRegistrationController::class, 'destroy']);
        })->add(new AuthMiddleware());
    }
}
