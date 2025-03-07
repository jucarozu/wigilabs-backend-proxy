<?php
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$environment = getenv('APP_ENV') ?: 'dev';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config/env', ".env.$environment");
$dotenv->load();

// Route-based routing
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_starts_with($path, '/ci/')) {
    require __DIR__ . '/../src/CodeIgniter/public/index.php';
} elseif (str_starts_with($path, '/slim/')) {
    require __DIR__ . '/../src/Slim/public/index.php';
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
