<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\DJController;

class DJRoutes
{
    public static function register(App $app): void
    {
        $app->get('/dj', [DJController::class, 'index']);
        $app->post('/dj', [DJController::class, 'store']);
        $app->get('/dj/{id}', [DJController::class, 'show']);
        $app->put('/dj/{id}', [DJController::class, 'update']);
        $app->delete('/dj/{id}', [DJController::class, 'destroy']);
    }
}
