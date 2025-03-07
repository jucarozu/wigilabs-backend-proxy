<?php

use Wigilabs\Common\Cache\RedisAdapter;
use Wigilabs\Common\Logger\MonologAdapter;

return function ($container) {
    $container->set('external_client', function ($container) {
        return (require __DIR__ . '/../../config/services.php')['external_client'];
    });

    $container->set('cache', function () {
        return new RedisAdapter($_ENV['REDIS_HOST']);
    });

    $container->set('logger', function () {
        return new MonologAdapter($_ENV['MONOLOG_NAME']);
    });
};
