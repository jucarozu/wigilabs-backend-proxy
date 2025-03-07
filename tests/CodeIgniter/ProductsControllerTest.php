<?php
namespace Tests\CodeIgniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\Response;
use Wigilabs\CodeIgniter\Controllers\ProductsController;
use Wigilabs\Common\Clients\ExternalClientInterface;
use Wigilabs\Common\Exceptions\ExternalServiceException;

class ProductsControllerTest extends CIUnitTestCase
{
    private $soapClient;
    private $restClient;
    private ProductsController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock external clients
        $this->soapClient = $this->createMock(ExternalClientInterface::class);
        $this->restClient = $this->createMock(ExternalClientInterface::class);

        // Initialize controller with mocked dependencies
        $this->controller = new ProductsController(
            $this->soapClient,
            $this->restClient
        );
    }

    public function testGetProductSoapEndpointReturnsValidJson()
    {
        // Configure mock
        $this->soapClient->method('getProduct')
            ->with('123')
            ->willReturn(['id' => '123', 'name' => 'Test Product']);

        // Execute request
        $response = $this->controller->getProductSoap('123');

        // Verify response
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $jsonData = json_decode($response->getJSON(), true);
        $this->assertEquals('Test Product', $jsonData['name']);
    }

    public function testGetProductSoapHandlesExternalServiceFailure()
    {
        // Configure mock to throw exception
        $this->soapClient->method('getProduct')
            ->willThrowException(new ExternalServiceException('Service unavailable'));

        // Execute request
        $response = $this->controller->getProductSoap('123');

        // Verify error response
        $this->assertEquals(503, $response->getStatusCode());
        $this->assertStringContainsString('Service unavailable', $response->getBody());
    }

    public function testGetProductRestEndpointReturnsValidJson()
    {
        // Configure mock
        $this->restClient->method('getProduct')
            ->with('456')
            ->willReturn(['id' => '456', 'name' => 'Rest Product']);

        // Execute request
        $response = $this->controller->getProductRest('456');

        // Verify response
        $jsonData = json_decode($response->getJSON(), true);
        $this->assertEquals('Rest Product', $jsonData['name']);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetProductRestHandlesExternalServiceFailure()
    {
        // Configure mock to throw exception
        $this->restClient->method('getProduct')
            ->willThrowException(new ExternalServiceException('Timeout error'));

        // Execute request
        $response = $this->controller->getProductRest('456');

        // Verify error handling
        $this->assertEquals(504, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            '{"error":"Timeout error"}',
            $response->getJSON()
        );
    }
}