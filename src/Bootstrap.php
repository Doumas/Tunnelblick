<?php

namespace App;

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use App\Routes\Routes;
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory; // Import für ResponseFactory


require __DIR__ . '/../vendor/autoload.php';

// ContainerBuilder erstellen und konfigurieren
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../src/Dependencies.php');

// Dependency Injection Container bauen
$container = $containerBuilder->build();
AppFactory::setContainer($container);

// Slim App erstellen
$app = AppFactory::create();

// Basis-Pfad Middleware hinzufügen
$app->add(BasePathMiddleware::class);

// Logger konfigurieren und dem Container hinzufügen
$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));
$container->set(Logger::class, $logger);

// Datenbankverbindung testen
try {
    $capsule = $container->get('db');
    $capsule->getConnection()->getPdo();
} catch (\PDOException $e) {
    $logger->error('Database connection failed: ' . $e->getMessage());
    throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
}

// CSRF-Schutz initialisieren und dem Container hinzufügen
$responseFactory = $app->getResponseFactory();
$csrf = new Guard($responseFactory); // ResponseFactory an den CSRF-Guard übergeben
$container->set('csrf', $csrf);
$app->add($csrf);  // CSRF-Middleware zur Anwendung hinzufügen

// Routen laden
Routes::register($app);

// Fehlerbehandlung für 404 und andere Fehler
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, function ($request, \Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails) use ($app, $logger) {
    $logger->warning('404 Not Found: ' . $request->getUri());
    return $app->getResponseFactory()->createResponse()
        ->withHeader('Location', '/?message=Page%20not%20found')
        ->withStatus(302);
});

// Fehlerbehandlung für 405 Method Not Allowed
$errorMiddleware->setErrorHandler(HttpMethodNotAllowedException::class, function ($request, \Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails) use ($app, $logger) {
    $logger->warning('405 Method Not Allowed: ' . $request->getUri());
    return $app->getResponseFactory()->createResponse()
        ->withHeader('Location', '/?message=Method%20not%20allowed')
        ->withStatus(302);
});

// Rückgabe der App-Instanz
return $app;