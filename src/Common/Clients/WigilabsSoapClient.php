<?php
namespace Wigilabs\Common\Clients;

use SoapClient;
use SoapFault;
use Wigilabs\Common\Exceptions\ExternalServiceException;

class WigilabsSoapClient implements ExternalClientInterface {
    private SoapClient $client;

    /**
     * @throws SoapFault
     */
    public function __construct(string $wsdl) {
        $this->client = new SoapClient($wsdl);
    }

    /**
     * @param SoapClient $client
     * @return void
     */
    public function setSoapClient(SoapClient $client): void {
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