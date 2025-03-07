<?php
namespace Wigilabs\Common\Clients;

use Wigilabs\Common\Clients\ExternalClientInterface;
use Wigilabs\Common\Exceptions\ExternalServiceException;
use SoapClient;
use SoapFault;

class WigilabsSoapClient extends SoapClient implements ExternalClientInterface {
    private WigilabsSoapClient $client;

    public function __construct(string $wsdl) {
        parent::__construct($wsdl);
        $this->client = new WigilabsSoapClient($wsdl);
    }

    /**
     * @param WigilabsSoapClient $client
     * @return void
     */
    public function setSoapClient(WigilabsSoapClient $client): void {
        $this->client = $client;
    }

    public function getProduct(string $id): array {
        try {
            $response = $this->client->__soapCall('GetProduct', [['id' => $id]]);

            $data = json_decode(json_encode($response), true);

            return is_array($data) ? $data : [];

        } catch (SoapFault $e) {
            throw new ExternalServiceException(
                "SOAP Error: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}