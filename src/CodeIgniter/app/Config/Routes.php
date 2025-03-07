<?php
namespace Wigilabs\CodeIgniter\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('ci', function ($routes) {
    $routes->get('products/soap/(:num)', 'ProductsController::getProductSoap/$1');
    $routes->get('products/rest/(:num)', 'ProductsController::getProductRest/$1');
});