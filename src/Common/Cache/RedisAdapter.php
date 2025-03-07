<?php
namespace Wigilabs\Common\Cache;

use Predis\Client;

class RedisAdapter implements CacheInterface {
    private Client $client;

    public function __construct(string $host) {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => $host,
            'port' => 6379
        ]);
    }

    public function setClient(Client $client): void {
        $this->client = $client;
    }

    public function get(string $key): ?array {
        $data = $this->client->get($key);
        return $data ? json_decode($data, true) : null;
    }

    public function set(string $key, array $data, int $ttl = 3600): void {
        $this->client->setex($key, $ttl, json_encode($data));
    }
}