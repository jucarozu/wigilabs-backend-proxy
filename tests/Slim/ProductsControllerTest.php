<?php
namespace Tests\Slim;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Wigilabs\Common\Clients\ExternalClientInterface;
use Wigilabs\Common\Exceptions\ExternalServiceException;
use Wigilabs\Slim\Controllers\ProductsController;

class ProductsControllerTest extends TestCase
{
    private $soapClient;
    private $restClient;
    private ProductsController $controller;
    private ResponseFactory $responseFactory;

    protected function setUp(): void
    {
        $this->soapClient = $this->createMock(ExternalClientInterface::class);
        $this->restClient = $this->createMock(ExternalClientInterface::class);
        $this->responseFactory = new ResponseFactory();

        $this->controller = new ProductsController(
            $this->soapClient,
            $this->restClient
        );
    }

    public function testGetProductSoapEndpointReturnsValidJson()
    {
        // Configure mock
        $this->soapClient->method('getProduct')
            ->with('789')
            ->willReturn(['id' => '789', 'name' => 'Slim SOAP Product']);

        // Create request
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/products/soap/789')
            ->withAttribute('id', '789');

        $response = $this->controller->getProductSoap(
            $request,
            $this->responseFactory->createResponse(),
            ['id' => '789']
        );

        // Verify response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            ['id' => '789', 'name' => 'Slim SOAP Product'],
            json_decode((string)$response->getBody(), true)
        );
    }

    public function testGetProductSoapHandlesExternalServiceFailure()
    {
        $this->soapClient->method('getProduct')
            ->willThrowException(new ExternalServiceException('Auth failed'));

        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/products/soap/999');

        $response = $this->controller->getProductSoap(
            $request,
            $this->responseFactory->createResponse(),
            ['id' => '999']
        );

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            '{"error":"Auth failed"}',
            (string)$response->getBody()
        );
    }

    public function testGetProductRestEndpointReturnsValidJson()
    {
        $this->restClient->method('getProduct')
            ->with('321')
            ->willReturn(['id' => '321', 'price' => 99.99]);

        $response = $this->controller->getProductRest(
            (new ServerRequestFactory())->createServerRequest('GET', '/products/rest/321'),
            $this->responseFactory->createResponse(),
            ['id' => '321']
        );

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode((string)$response->getBody(), true);
        $this->assertEquals(99.99, $data['price']);
    }

    public function testGetProductRestHandlesExternalServiceFailure()
    {
        $this->restClient->method('getProduct')
            ->willThrowException(new ExternalServiceException('Invalid response'));

        $response = $this->controller->getProductRest(
            (new ServerRequestFactory())->createServerRequest('GET', '/products/rest/000'),
            $this->responseFactory->createResponse(),
            ['id' => '000']
        );

        $this->assertEquals(502, $response->getStatusCode());
        $this->assertStringContainsString('Invalid response', (string)$response->getBody());
    }
}