<?php
namespace Wigilabs\Common\Cache;

/**
 * Interface for cache systems.
 * Ensures consistency between different implementations (Redis, Memcached, etc.).
 */
interface CacheInterface {
    /**
     * Gets a cached value.
     *
     * @param string $key Identification key
     * @return array|null Data stored or null if not exists
     */
    public function get(string $key): ?array;

    /**
     * Stores a value in cache.
     *
     * @param string $key Identification key
     * @param array $data Data to be stored
     * @param int $ttl Lifetime in seconds
     */
    public function set(string $key, array $data, int $ttl = 3600): void;
}