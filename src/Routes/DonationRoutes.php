<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\DonationController;

class DonationRoutes
{
    public static function register(App $app): void
    {
        $app->get('/donations', [DonationController::class, 'index']);
        $app->post('/donations', [DonationController::class, 'store']);
        $app->get('/donations/{id}', [DonationController::class, 'show']);
    }
}
