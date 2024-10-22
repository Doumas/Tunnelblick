<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\AdminController;

class AdminRoutes
{
    public static function register(App $app): void
    {
        $app->get('/admin', [AdminController::class, 'dashboard']);
        $app->post('/admin/create', [AdminController::class, 'create']);
    }
}
