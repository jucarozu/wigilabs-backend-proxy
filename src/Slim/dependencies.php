<?php
return function ($container) {
    $container->set('external_client', function ($container) {
        return (require __DIR__ . '/../../config/services.php')['external_client'];
    });

    $container->set('cache', function () {
        return (require __DIR__ . '/../../config/services.php')['cache'];
    });

    $container->set('logger', function () {
        return (require __DIR__ . '/../../config/services.php')['logger'];
    });
};
