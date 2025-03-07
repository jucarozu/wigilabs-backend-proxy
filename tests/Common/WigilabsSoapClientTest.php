<?php
namespace Tests\Common;

use Wigilabs\Common\Clients\WigilabsSoapClient;
use Wigilabs\Common\Exceptions\ExternalServiceException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use SoapFault;

class WigilabsSoapClientTest extends TestCase
{
    private $soapMock;
    private WigilabsSoapClient $client;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->soapMock = $this->createMock(WigilabsSoapClient::class);
        $this->client = new WigilabsSoapClient('https://fake-wsdl.wsdl');
        $this->client->setSoapClient($this->soapMock);
    }

    /** @test */
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

    /** @test
     * @throws SoapFault
     */
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