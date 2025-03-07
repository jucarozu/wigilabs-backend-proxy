<?php
namespace Wigilabs\Common\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Wigilabs\Common\Exceptions\ExternalServiceException;

class WigilabsRestClient implements ExternalClientInterface {
    private Client $client;

    public function __construct(string $baseUri, string $apiKey = '') {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout'  => 5.0,
            'headers' => [
                'Accept' => 'application/json',
                'X-API-Key' => $apiKey
            ]
        ]);
    }

    public function setGuzzleClient(Client $client): void {
        $this->client = $client;
    }

    public function getProduct(string $id): array {
        try {
            $response = $this->client->get("/products/{$id}");

            if ($response->getStatusCode() !== 200) {
                throw new ExternalServiceException("HTTP Error " . $response->getStatusCode());
            }

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            throw new ExternalServiceException(
                "REST service failure: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}