<?php
namespace Wigilabs\Common\Decorators;

use Wigilabs\Common\Clients\ExternalClientInterface;
use Wigilabs\Common\Cache\CacheInterface;

class CachedClient implements ExternalClientInterface {
    private ExternalClientInterface $client;
    private CacheInterface $cache;
    private int $ttl;

    public function __construct(
        ExternalClientInterface $client,
        CacheInterface $cache,
        int $ttl = 300
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function getProduct(string $id): array {
        $cacheKey = "product_{$id}";

        if ($data = $this->cache->get($cacheKey)) {
            return $data;
        }

        $data = $this->client->getProduct($id);
        $this->cache->set($cacheKey, $data, $this->ttl);

        return $data;
    }
}
