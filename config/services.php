<?php
use Wigilabs\Common\Cache\RedisAdapter;
use Wigilabs\Common\Clients\WigilabsRestClient;
use Wigilabs\Common\Clients\WigilabsSoapClient;
use Wigilabs\Common\Decorators\CachedClient;
use Wigilabs\Common\Decorators\LoggedClient;
use Wigilabs\Common\Logger\MonologAdapter;

return [
    'external_client' => function () {
        $apiVersion = $_ENV['EXTERNAL_API_VERSION'] ?? 'rest';

        switch ($apiVersion) {
            case 'soap':
                $baseClient = new WigilabsSoapClient(
                    $_ENV['SOAP_WSDL']
                );
                break;

            case 'rest':
                $baseClient = new WigilabsRestClient(
                    $_ENV['REST_BASE_URI'],
                    $_ENV['REST_API_KEY'] ?? ''
                );
                break;

            default:
                throw new InvalidArgumentException('API not supported');
        }

        $cachedClient = new CachedClient(
            $baseClient,
            config('cache'),
            $_ENV['CACHE_TTL'] ?? 300
        );

        return new LoggedClient(
            $cachedClient,
            config('logger'),
            $apiVersion === 'soap' ? 'SOAP Service' : 'REST Service'
        );
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