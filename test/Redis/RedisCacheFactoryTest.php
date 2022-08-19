<?php
declare(strict_types=1);

namespace LessCacheTest\Redis;

use LessCache\Redis\RedisCache;
use LessCache\Redis\RedisCacheFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \LessCache\Redis\RedisCacheFactory
 */
final class RedisCacheFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $config = [
            RedisCache::class => [
                'host' => 'redis',
                'keyPrefix' => 'fiz',
            ],
        ];

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $factory = new RedisCacheFactory();
        $service = $factory($container);

        self::assertInstanceOf(RedisCache::class, $service);
    }
}
