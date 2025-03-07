<?php
namespace Tests\Common;

use Monolog\Handler\StreamHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Wigilabs\Common\Logger\LoggerInterface;
use Wigilabs\Common\Logger\MonologAdapter;

class MonologAdapterTest extends TestCase
{
    private MonologAdapter $adapter;
    private TestHandler $testHandler;

    protected function setUp(): void
    {
        $this->testHandler = new TestHandler();
        $logger = new Logger('test');
        $logger->pushHandler($this->testHandler);

        $this->adapter = new MonologAdapter('test');
        $this->adapter->setLogger($logger);
    }

    public function testImplementsLoggerInterface()
    {
        $this->assertInstanceOf(
            LoggerInterface::class,
            $this->adapter
        );
    }

    public function testLogLevelMethods()
    {
        $this->adapter->emergency('Emergency test', ['code' => 500]);
        $this->adapter->alert('Alert test', ['count' => 5]);
        $this->adapter->critical('Critical test', ['file' => 'app.php']);
        $this->adapter->error('Error test', ['user' => 'admin']);
        $this->adapter->warning('Warning test', ['ip' => '127.0.0.1']);
        $this->adapter->notice('Notice test', ['action' => 'update']);
        $this->adapter->info('Info test', ['status' => 'ok']);
        $this->adapter->debug('Debug test', ['query' => 'SELECT *']);

        $this->assertTrue($this->testHandler->hasEmergency('Emergency test'));
        $this->assertTrue($this->testHandler->hasAlert('Alert test'));
        $this->assertTrue($this->testHandler->hasCritical('Critical test'));
        $this->assertTrue($this->testHandler->hasError('Error test'));
        $this->assertTrue($this->testHandler->hasWarning('Warning test'));
        $this->assertTrue($this->testHandler->hasNotice('Notice test'));
        $this->assertTrue($this->testHandler->hasInfo('Info test'));
        $this->assertTrue($this->testHandler->hasDebug('Debug test'));

        $records = $this->testHandler->getRecords();
        $this->assertEquals(500, $records[0]['context']['code']);
        $this->assertEquals('admin', $records[3]['context']['user']);
    }

    public function testLogMethodWithDifferentLevels()
    {
        $this->adapter->log(Logger::INFO, 'Custom log', ['source' => 'test']);
        $this->assertTrue($this->testHandler->hasInfo('Custom log'));
    }

    public function testDefaultHandlerConfiguration()
    {
        $adapter = new MonologAdapter('prod');
        $handlers = $adapter->getLogger()->getHandlers();

        $this->assertCount(1, $handlers);
        $this->assertInstanceOf(
            StreamHandler::class,
            $handlers[0]
        );
    }

    public function testSetLoggerMethod()
    {
        $newLogger = new Logger('new_logger');
        $newHandler = new TestHandler();
        $newLogger->pushHandler($newHandler);

        $this->adapter->setLogger($newLogger);
        $this->adapter->info('Test message');

        $this->assertTrue($newHandler->hasInfo('Test message'));
    }

    public function testLogLevelFromEnvironment()
    {
        $_ENV['LOG_LEVEL'] = Logger::DEBUG;
        $adapter = new MonologAdapter('test');
        $handler = $adapter->getLogger()->getHandlers()[0];

        $this->assertEquals(Logger::DEBUG, $handler->getLevel());
        unset($_ENV['LOG_LEVEL']);
    }

    private function getLoggerProperty(): Logger
    {
        $reflection = new \ReflectionClass($this->adapter);
        $property = $reflection->getProperty('logger');
        $property->setAccessible(true);

        return $property->getValue($this->adapter);
    }
}