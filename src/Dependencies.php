<?php

namespace App\Dependencies;

use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    // Einstellungen laden
    'settings' => function (): array {
        $settings = require __DIR__ . '/../config/settings.php';

        if (empty($settings['settings'])) {
            throw new \RuntimeException('Settings not loaded correctly.');
        }

        return $settings['settings'];
    },
    
    // Datenbankverbindung einrichten
    'db' => function (ContainerInterface $container): Capsule {
        $settings = $container->get('settings');

        if (empty($settings['db'])) {
            throw new \RuntimeException('Database settings are not configured properly.');
        }

        $capsule = new Capsule();
        $capsule->addConnection($settings['db']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    },

    // ResponseFactory einrichten
    ResponseFactoryInterface::class => function (): ResponseFactory {
        return new ResponseFactory();
    },

    // CSRF-Schutz einrichten
    'csrf' => function (ContainerInterface $container): Guard {
        $responseFactory = $container->get(ResponseFactoryInterface::class);
        $settings = $container->get('settings');

        $csrfSecret = $settings['csrf']['secret'] ?? 'default_csrf_secret';
        $csrfPrefix = $settings['csrf']['prefix'] ?? 'csrf_';

        return new Guard($responseFactory, $csrfPrefix, $csrfSecret);
    },
];
