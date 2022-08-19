<?php
declare(strict_types=1);

namespace LessCacheTest;

use DateInterval;
use LessCache\RequestCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessCache\RequestCache
 */
final class RequestCacheTest extends TestCase
{
    public function testSet(): void
    {
        $cache = new RequestCache();

        $cache->set('fiz', 'biz');

        self::assertSame('biz', $cache->get('fiz'));
    }

    public function testSetTtlInt(): void
    {
        $cache = new RequestCache();

        $cache->set('fiz', 'biz', -1);

        self::assertSame('bar', $cache->get('fiz', 'bar'));
    }

    public function testSetTtlInterval(): void
    {
        $cache = new RequestCache();

        $interval = new DateInterval('PT0S');
        $cache->set('fiz', 'biz', $interval);

        self::assertSame('bar', $cache->get('fiz', 'bar'));
    }

    public function testHas(): void
    {
        $cache = new RequestCache();

        self::assertFalse($cache->has('fiz'));
        $cache->set('fiz', 'biz');
        self::assertTrue($cache->has('fiz'));
        $cache->clear();
        self::assertFalse($cache->has('fiz'));
    }
}
