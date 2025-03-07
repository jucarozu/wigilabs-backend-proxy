<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../../../vendor/autoload.php';

// Load environment variables
$environment = getenv('APP_ENV') ?: 'prod';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../config/env', ".env.$environment");
$dotenv->load();

// Create Slim application
$app = AppFactory::create();

// Setting up dependencies
(require __DIR__ . '/../../../src/Slim/dependencies.php')($app);

// Register routes
(require __DIR__ . '/../../../src/Slim/src/Routes/products.php')($app);

// Middleware for JSON parsing
$app->addBodyParsingMiddleware();

// Run application
$app->run();
