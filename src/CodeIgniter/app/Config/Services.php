<?php
namespace Wigilabs\CodeIgniter\Config;

use CodeIgniter\Config\BaseService;
use Wigilabs\Common\Cache\RedisAdapter;
use Wigilabs\Common\Clients\WigilabsSoapClient;
use SoapFault;
use Wigilabs\Common\Logger\MonologAdapter;

class Services extends BaseService {
    /**
     * @param bool $getShared
     * @return WigilabsSoapClient|object
     * @throws SoapFault
     */
    public static function externalClient(bool $getShared = true) {
        return $getShared
            ? static::getSharedInstance('external_client')
            : (include __DIR__ . '/../../../../config/services.php')['external_client'];
    }

    /**
     * @param bool $getShared
     * @return object|RedisAdapter
     */
    public static function cache(bool $getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('cache');
        }
        return new RedisAdapter($_ENV['REDIS_HOST']);
    }

    /**
     * @param bool $getShared
     * @return object|MonologAdapter
     */
    public static function logger(bool $getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('logger');
        }
        return new MonologAdapter($_ENV['MONOLOG_NAME']);
    }
}