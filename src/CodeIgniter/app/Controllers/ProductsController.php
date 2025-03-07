<?php
namespace Wigilabs\CodeIgniter\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use Wigilabs\Common\Clients\ExternalClientInterface;

class ProductsController extends Controller {
    private ExternalClientInterface $soapClient;
    private ExternalClientInterface $restClient;

    public function __construct(
        ExternalClientInterface $soapClient,
        ExternalClientInterface $restClient
    ) {
        $this->soapClient = $soapClient;
        $this->restClient = $restClient;
    }

    /**
     * SOAP Endpoint: Gets a product using the SOAP client.
     */
    public function getProductSoap($id): ResponseInterface
    {
        $data = $this->soapClient->getProduct($id);
        return $this->response->setJSON($data);
    }

    /**
     * REST Endpoint: Get a product using the REST client.
     */
    public function getProductRest($id): ResponseInterface
    {
        $data = $this->restClient->getProduct($id);
        return $this->response->setJSON($data);
    }
}