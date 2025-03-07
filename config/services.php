<?php
use Wigilabs\Common\Cache\RedisAdapter;
use Wigilabs\Common\Factories\ExternalClientFactory;
use Wigilabs\Common\Logger\MonologAdapter;

return [
    'external_service' => function () {
        return [
            'api_version' => $_ENV['EXTERNAL_API_VERSION'],
            'soap' => [
                'wsdl' => $_ENV['SOAP_WSDL'] ?? '',
            ],
            'rest' => [
                'base_uri' => $_ENV['REST_BASE_URI'] ?? '',
                'api_key' => $_ENV['REST_API_KEY'] ?? '',
            ]
        ];
    },

    'external_client' => function () {
        $factory = new ExternalClientFactory(
            config('cache'),
            config('logger')
        );

        return $factory->create();
    },

    'cache' => function () {
        return new RedisAdapter($_ENV['REDIS_HOST'] ?? 'localhost');
    },

    'logger' => function () {
        return new MonologAdapter($_ENV['MONOLOG_NAME'] ?? 'wigilabs-backend-proxy');
    },

    'db' => function () {
        return [
            'host' => $_ENV['DB_HOST'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'database' => $_ENV['DB_NAME'],
        ];
    }
];