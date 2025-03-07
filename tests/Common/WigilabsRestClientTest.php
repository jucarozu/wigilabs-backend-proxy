<?php
namespace Tests\Common;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Wigilabs\Common\Clients\WigilabsRestClient;
use Wigilabs\Common\Exceptions\ExternalServiceException;

class WigilabsRestClientTest extends TestCase
{
    private MockHandler $mockHandler;
    private WigilabsRestClient $restClient;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handler = HandlerStack::create($this->mockHandler);

        $this->restClient = new WigilabsRestClient('https://fake-api.com/');
        $this->restClient->setGuzzleClient(new Client(['handler' => $handler]));
    }

    public function testSuccessfulGetProduct()
    {
        $this->mockHandler->append(
            new Response(200, [], json_encode(['id' => 123, 'name' => 'Test Product']))
        );

        $result = $this->restClient->getProduct('123');

        $this->assertEquals([
            'id' => 123,
            'name' => 'Test Product'
        ], $result);
    }

    public function testHandlesHttpErrors()
    {
        $this->mockHandler->append(new Response(500));

        $this->expectException(ExternalServiceException::class);
        $this->expectExceptionMessage('HTTP Error 500');

        $this->restClient->getProduct('456');
    }
}