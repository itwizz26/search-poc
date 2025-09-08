<?php
require __DIR__ . '/vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;

// Settings
$settings = require __DIR__ . '/config/settings.php';

// Container
$container = new Container();

// Load dependencies — must be a callable
$dependencies = require __DIR__ . '/config/dependencies.php';
$dependencies($container, $settings); 

// Create Slim app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Middlewares
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// CORS
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, OPTIONS');
});

// Load routes
require __DIR__ . '/config/routes.php';

return $app;
