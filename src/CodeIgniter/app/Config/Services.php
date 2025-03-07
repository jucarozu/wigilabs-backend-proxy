<?php
namespace Wigilabs\CodeIgniter\Config;

use CodeIgniter\Config\BaseService;
use Wigilabs\Common\Cache\CacheInterface;
use Wigilabs\Common\Clients\ExternalClientInterface;
use Wigilabs\Common\Logger\LoggerInterface;

class Services extends BaseService {
    /**
     * @param bool $getShared
     * @return ExternalClientInterface|object
     */
    public static function externalClient(bool $getShared = true) {
        return $getShared
            ? static::getSharedInstance('external_client')
            : (include __DIR__ . '/../../../../config/services.php')['external_client'];
    }

    /**
     * @param bool $getShared
     * @return CacheInterface|object
     */
    public static function cache(bool $getShared = true) {
        return $getShared
            ? static::getSharedInstance('cache')
            : (include __DIR__ . '/../../../../config/services.php')['cache'];
    }

    /**
     * @param bool $getShared
     * @return LoggerInterface|object
     */
    public static function logger(bool $getShared = true) {
        return $getShared
            ? static::getSharedInstance('logger')
            : (include __DIR__ . '/../../../../config/services.php')['logger'];
    }
}