<?php
declare(strict_types=1);

namespace LessCacheTest\Redis;

use LessCache\Redis\RedisCache;
use LessCache\Redis\RedisCacheFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Redis;

/**
 * @covers \LessCache\Redis\RedisCacheFactory
 */
final class RedisCacheFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $redis = $this->createMock(Redis::class);

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects(self::once())
            ->method('get')
            ->with(Redis::class)
            ->willReturn($redis);

        $factory = new RedisCacheFactory();
        $service = $factory($container);

        self::assertInstanceOf(RedisCache::class, $service);
    }
}
