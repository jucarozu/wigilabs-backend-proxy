<?php
use Slim\Routing\RouteCollectorProxy;

return function (RouteCollectorProxy $app) {
    $app->group('/slim/products', function ($group) {
        $group->get('/soap/{id}', 'ProductsController:getProductSoap');
        $group->get('/rest/{id}', 'ProductsController:getProductRest');
    });
};