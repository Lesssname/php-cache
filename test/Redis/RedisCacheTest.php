<?php
declare(strict_types=1);

namespace LessCacheTest\Redis;

use DateInterval;
use LessCache\Redis\RedisCache;
use PHPUnit\Framework\TestCase;
use Redis;

/**
 * @covers \LessCache\Redis\RedisCache
 */
final class RedisCacheTest extends TestCase
{
    public function testGet(): void
    {
        $redis = $this->createMock(Redis::class);
        $redis
            ->expects(self::once())
            ->method('get')
            ->with('bar')
            ->willReturn(serialize('foo'));

        $redisCache = new RedisCache($redis);

        self::assertSame('foo', $redisCache->get('bar', 'biz'));
    }

    public function testGetNotSet(): void
    {
        $redis = $this->createMock(Redis::class);
        $redis
            ->expects(self::once())
            ->method('get')
            ->with('bar')
            ->willReturn(false);

        $redisCache = new RedisCache($redis);

        self::assertSame('biz', $redisCache->get('bar', 'biz'));
    }

    public function testSetWithoutTtl(): void
    {
        $redis = $this->createMock(Redis::class);
        $redis
            ->expects(self::once())
            ->method('set')
            ->with('bar', serialize('foo'))
            ->willReturn(true);

        $redisCache = new RedisCache($redis);
        self::assertTrue($redisCache->set('bar', 'foo'));
    }

    public function testSetWithTtl(): void
    {
        $ttl = new DateInterval('P1D');

        $redis = $this->createMock(Redis::class);
        $redis
            ->expects(self::once())
            ->method('setEx')
            ->with('bar', 86_400, serialize('foo'))
            ->willReturn(true);

        $redisCache = new RedisCache($redis);
        self::assertTrue($redisCache->set('bar', 'foo', $ttl));
    }

    public function testDelete(): void
    {
        $redis = $this->createMock(Redis::class);
        $redis
            ->expects(self::once())
            ->method('del')
            ->with('bar')
            ->willReturn(0);

        $redisCache = new RedisCache($redis);
        self::assertTrue($redisCache->delete('bar'));
    }

    public function testExists(): void
    {
        $redis = $this->createMock(Redis::class);
        $redis
            ->expects(self::once())
            ->method('flushDB')
            ->willReturn(true);

        $redisCache = new RedisCache($redis);
        self::assertTrue($redisCache->clear());
    }

    public function testHas(): void
    {
        $redis = $this->createMock(Redis::class);
        $redis
            ->expects(self::once())
            ->method('exists')
            ->with('bar')
            ->willReturn(1);

        $redisCache = new RedisCache($redis);
        self::assertTrue($redisCache->has('bar'));
    }
}
