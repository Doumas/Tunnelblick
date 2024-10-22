<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\BlogPostController;

class BlogPostRoutes
{
    public static function register(App $app): void
    {
        $app->get('/blog', [BlogPostController::class, 'index']);
        $app->post('/blog', [BlogPostController::class, 'store']);
        $app->get('/blog/{id}', [BlogPostController::class, 'show']);
        $app->put('/blog/{id}', [BlogPostController::class, 'update']);
        $app->delete('/blog/{id}', [BlogPostController::class, 'destroy']);
    }
}
