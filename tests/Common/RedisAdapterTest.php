<?php
namespace Tests\Common;

use Wigilabs\Common\Cache\RedisAdapter;
use PHPUnit\Framework\MockObject\Exception;
use Predis\Client;
use PHPUnit\Framework\TestCase;

class RedisAdapterTest extends TestCase
{
    private $redisMock;
    private RedisAdapter $adapter;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->redisMock = $this->createMock(Client::class);
        $this->adapter = new RedisAdapter('localhost');
        $this->adapter->setClient($this->redisMock);
    }

    public function testGetReturnsDecodedData()
    {
        $this->redisMock->method('get')
            ->willReturn(json_encode(['test' => 'data']));

        $result = $this->adapter->get('test_key');
        $this->assertEquals(['test' => 'data'], $result);
    }

    public function testSetEncodesDataAndSavesWithTTL()
    {
        $this->redisMock->expects($this->once())
            ->method('setex')
            ->with('test_key', 300, json_encode(['test' => 'data']));

        $this->adapter->set('test_key', ['test' => 'data'], 300);
    }
}