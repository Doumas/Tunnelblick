<?php

namespace App\Routes;

use Slim\App;

class Routes
{
    public static function register(App $app): void
    {
        // Alle Routen in separaten Dateien registrieren
        AuthRoutes::register($app);
        AdminRoutes::register($app);
        BlogPostRoutes::register($app);
        CommentRoutes::register($app);
        DJRoutes::register($app);
        DonationRoutes::register($app);
        EventRegistrationRoutes::register($app);
        HomeRoutes::register($app);
        MediaRoutes::register($app);
    }
}
