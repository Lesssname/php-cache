<?php
declare(strict_types=1);

namespace LessCacheTest\Redis;

use LessCache\Redis\RedisFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Redis;

/**
 * @covers \LessCache\Redis\RedisFactory
 */
final class RedisFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $config = [
            Redis::class => [
                'host' => 'redis',
                'keyPrefix' => 'fiz',
            ],
        ];

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $factory = new RedisFactory();
        $redis = $factory($container);

        self::assertInstanceOf(Redis::class, $redis);
        self::assertTrue($redis->isConnected());
        self::assertSame('fiz', $redis->getOption(Redis::OPT_PREFIX));
    }
}
