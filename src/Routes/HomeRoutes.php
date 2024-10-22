<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\HomeController;

class HomeRoutes
{
    public static function register(App $app): void
    {
        $app->get('/', [HomeController::class, 'index']);
    }
}
