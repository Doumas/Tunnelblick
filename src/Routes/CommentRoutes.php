<?php

namespace App\Routes;

use Slim\App;
use App\Controllers\CommentController;

class CommentRoutes
{
    public static function register(App $app): void
    {
        $app->post('/comment', [CommentController::class, 'store']);
        $app->delete('/comment/{id}', [CommentController::class, 'destroy']);
    }
}
