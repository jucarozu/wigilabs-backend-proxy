<?php
namespace Wigilabs\Common\Factories;

use SoapFault;
use InvalidArgumentException;
use Wigilabs\Common\Clients\ExternalClientInterface;
use Wigilabs\Common\Clients\WigilabsRestClient;
use Wigilabs\Common\Clients\WigilabsSoapClient;
use Wigilabs\Common\Decorators\CachedClient;
use Wigilabs\Common\Decorators\LoggedClient;
use Wigilabs\Common\Cache\CacheInterface;
use Wigilabs\Common\Logger\LoggerInterface;

class ExternalClientFactory
{
    private CacheInterface $cache;
    private LoggerInterface $logger;
    private string $apiVersion;

    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->apiVersion = config('external_service.api_version');
    }

    /**
     * Creates a decorated client based on environment configuration
     *
     * @throws InvalidArgumentException|SoapFault For unsupported API types
     */
    public function create(): ExternalClientInterface
    {
        $baseClient = $this->createBaseClient();

        return $this->applyDecorators($baseClient);
    }

    /**
     * Instantiates the base client implementation.
     * @throws SoapFault
     */
    private function createBaseClient(): ExternalClientInterface
    {
        switch ($this->apiVersion) {
            case 'soap':
                return new WigilabsSoapClient(
                    config('external_service.soap.wsdl')
                );

            case 'rest':
                return new WigilabsRestClient(
                    config('external_service.rest.base_uri'),
                    config('external_service.rest.api_key'),
                );

            default:
                throw new InvalidArgumentException(
                    "Unsupported API type: {$this->apiVersion}. Valid values: soap, rest"
                );
        }
    }

    /**
     * Applies caching and logging decorators.
     */
    private function applyDecorators(ExternalClientInterface $baseClient): ExternalClientInterface
    {
        $cachedClient = new CachedClient(
            $baseClient,
            $this->cache,
            $_ENV['CACHE_TTL'] ?? 300
        );

        return new LoggedClient(
            $cachedClient,
            $this->logger,
            $this->apiVersion === 'soap' ? 'SOAP Service' : 'REST Service'
        );
    }
}