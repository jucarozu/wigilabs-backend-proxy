<?php
namespace Wigilabs\Common\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MonologAdapter implements LoggerInterface {
    private Logger $logger;

    public function __construct(string $name) {
        $this->logger = new Logger($name);

        $this->logger->pushHandler(
            new StreamHandler(
                __DIR__ . '/../logs/app.log',
                $_ENV['LOG_LEVEL'] ?? Logger::ERROR
            )
        );
    }

    public function getLogger(): Logger {
        return $this->logger;
    }

    public function setLogger(Logger $logger): void {
        $this->logger = $logger;
    }

    public function alert($message, array $context = []) {
        $this->logger->alert($message, $context);
    }

    public function critical($message, array $context = []) {
        $this->logger->critical($message, $context);
    }

    public function debug($message, array $context = []) {
        $this->logger->debug($message, $context);
    }

    public function emergency($message, array $context = []) {
        $this->logger->emergency($message, $context);
    }

    public function error($message, array $context = []) {
        $this->logger->error($message, $context);
    }

    public function info($message, array $context = []) {
        $this->logger->info($message, $context);
    }

    public function log($level, $message, array $context = []) {
        $this->logger->log($level, $message, $context);
    }

    public function notice($message, array $context = []) {
        $this->logger->notice($message, $context);
    }

    public function warning($message, array $context = []) {
        $this->logger->warning($message, $context);
    }
}