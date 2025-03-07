<?php
namespace Wigilabs\Slim\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wigilabs\Common\Clients\ExternalClientInterface;

class ProductsController {
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
    public function getProductSoap(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $data = $this->soapClient->getProduct($args['id']);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * REST Endpoint: Get a product using the REST client.
     */
    public function getProductRest(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $data = $this->restClient->getProduct($args['id']);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}