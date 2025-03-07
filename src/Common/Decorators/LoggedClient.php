<?php
namespace Wigilabs\Common\Decorators;

use Wigilabs\Common\Clients\ExternalClientInterface;
use Wigilabs\Common\Logger\LoggerInterface;

class LoggedClient implements ExternalClientInterface {
    private ExternalClientInterface $client;
    private LoggerInterface $logger;
    private string $serviceName;

    public function __construct(
        ExternalClientInterface $client,
        LoggerInterface $logger,
        string $serviceName = 'external_service'
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->serviceName = $serviceName;
    }

    /**
     * @throws \Exception
     */
    public function getProduct(string $id): array {
        $this->logger->info("Starting request to {$this->serviceName}", [
            'action' => 'getProduct',
            'product_id' => $id
        ]);

        try {
            $data = $this->client->getProduct($id);

            $this->logger->info("Successful request to {$this->serviceName}", [
                'product_id' => $id,
                'response_size' => count($data)
            ]);

            return $data;
        } catch (\Exception $e) {
            $this->logger->error("Error in {$this->serviceName}", [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            throw $e;
        }
    }
}