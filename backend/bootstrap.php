<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

require __DIR__ . '/vendor/autoload.php';

// Load settings
$settings = require __DIR__ . '/config/settings.php';

// Create PHP-DI container
$container = new Container();
$container->set('settings', $settings['settings']);

// Load dependencies (e.g., $container->set('db', ...))
require __DIR__ . '/config/dependencies.php';

// Set container to AppFactory
AppFactory::setContainer($container);

// Create Slim app
$app = AppFactory::create();

// Middleware
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Handle OPTIONS preflight for all routes
$app->options('/{routes:.+}', function ($request, Response $response) {
    return $response;
});

// Load routes
(require __DIR__ . '/config/routes.php')($app);

// Error middleware
$app->addErrorMiddleware(true, true, true);

// Return app for index.php
return $app;