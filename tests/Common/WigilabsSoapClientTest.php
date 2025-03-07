<?php
namespace Tests\Common;

use SoapClient;
use SoapFault;
use Wigilabs\Common\Clients\WigilabsSoapClient;
use Wigilabs\Common\Exceptions\ExternalServiceException;
use PHPUnit\Framework\TestCase;

class WigilabsSoapClientTest extends TestCase
{
    private $soapMock;
    private WigilabsSoapClient $client;

    /**
     */
    protected function setUp(): void
    {
        $this->soapMock = $this->createMock(SoapClient::class);
        $this->client = new WigilabsSoapClient('https://fake-wsdl.wsdl');
        $this->client->setSoapClient($this->soapMock);
    }

    public function testSuccessfulGetProduct()
    {
        $validResponse = new \stdClass();
        $validResponse->id = 123;
        $validResponse->name = "Test Product";

        $this->soapMock->method('__soapCall')
            ->with('GetProduct', [['id' => '123']])
            ->willReturn($validResponse);

        $result = $this->client->getProduct('123');

        $this->assertEquals([
            'id' => 123,
            'name' => 'Test Product'
        ], $result);
    }

    public function testHandlesSoapFaults()
    {
        // Mock de error SOAP
        $this->soapMock->method('__soapCall')
            ->willThrowException(new SoapFault('Server', 'Internal Server Error'));

        $this->expectException(ExternalServiceException::class);
        $this->expectExceptionMessage('SOAP Error: Internal Server Error');

        $this->client->getProduct('456');
    }
}