<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\BlogPostMediaController;

class MediaRoutes
{
    public static function register(App $app): void
    {
        $app->post('/media/upload', [BlogPostMediaController::class, 'upload']);
        $app->get('/media/{id}', [BlogPostMediaController::class, 'show']);
    }
}
