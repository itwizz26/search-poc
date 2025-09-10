<?php
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// Load settings
$settings = require __DIR__ . '/config/settings.php';

// Create PHP-DI container
$container = new Container();

// Make settings available in container
$container->set('settings', $settings['settings']);

// Load dependencies
require __DIR__ . '/config/dependencies.php';

// Set container to AppFactory
AppFactory::setContainer($container);

// Create Slim app
$app = AppFactory::create();

// Middleware: CORS, JSON parsing, etc.
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Load routes
require __DIR__ . '/config/routes.php';

// Error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

return $app;