<?php
use CodeIgniter\CodeIgniter;

// Define the essential routes
const APPPATH = __DIR__ . '/../../../src/CodeIgniter/app/';
const SYSTEMPATH = __DIR__ . '/../../../vendor/codeigniter4/framework/system/';
const ROOTPATH = __DIR__ . '/../../../';
const FCPATH = __DIR__ . '/';

// Loading the Composer autoloader and framework
require_once SYSTEMPATH . 'bootstrap.php';

// Create a CodeIgniter instance
$app = new CodeIgniter(config('App'));

try {
    // Set context and run
    $app->initialize();
    $app->setContext('web');
    $app->run();
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');

    if (function_exists('log_message')) {
        log_message('critical', $e->getMessage());
    }

    echo json_encode([
        'error' => 'Internal Server Error',
        'code' => 500
    ]);

    exit;
}