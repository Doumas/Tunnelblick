<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\AuthController;

class AuthRoutes
{
    public static function register(App $app): void
    {
        $app->post('/login', [AuthController::class, 'login']);
        $app->post('/register', [AuthController::class, 'register']);
    }
}
