<?php
declare(strict_types=1);

namespace LessCacheTest;

use LessCache\AbstractCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessCache\AbstractCache
 */
final class AbstractCacheTest extends TestCase
{
    public function testGetMultiple(): void
    {
        $cache = $this
            ->getMockBuilder(AbstractCache::class)
            ->onlyMethods(['get', 'set', 'clear', 'delete', 'has'])
            ->getMock();

        $cache
            ->expects(self::exactly(2))
            ->method('get')
            ->willReturnMap(
                [
                    ['fiz', 'foo', 'ziz'],
                    ['biz', 'foo', 'foo'],
                ],
            );

        $results = $cache->getMultiple(
            ['fiz', 'biz'],
            'foo',
        );

        $results = iterator_to_array($results);

        self::assertSame(
            [
                'fiz' => 'ziz',
                'biz' => 'foo',
            ],
            $results,
        );
    }

    public function testSetMultiple(): void
    {
        $cache = $this
            ->getMockBuilder(AbstractCache::class)
            ->onlyMethods(['get', 'set', 'clear', 'delete', 'has'])
            ->getMock();
        $cache
            ->expects(self::exactly(2))
            ->method('set')
            ->willReturnMap(
                [
                    ['fiz', 'foo', 123, true],
                    ['biz', 'bar', 123, true],
                ],
            );

        $result = $cache->setMultiple(
            [
                'fiz' => 'foo',
                'biz' => 'bar',
            ],
            123,
        );

        self::assertTrue($result);
    }

    public function testSetMultipleFailure(): void
    {
        $cache = $this
            ->getMockBuilder(AbstractCache::class)
            ->onlyMethods(['get', 'set', 'clear', 'delete', 'has'])
            ->getMock();
        $cache
            ->expects(self::exactly(2))
            ->method('set')
            ->willReturnMap(
                [
                    ['fiz', 'foo', 123, true],
                    ['biz', 'bar', 123, false],
                ],
            );

        $result = $cache->setMultiple(
            [
                'fiz' => 'foo',
                'biz' => 'bar',
                'miz' => 'maz',
            ],
            123,
        );

        self::assertFalse($result);
    }

    public function testDeleteMultiple(): void
    {
        $cache = $this
            ->getMockBuilder(AbstractCache::class)
            ->onlyMethods(['get', 'set', 'clear', 'delete', 'has'])
            ->getMock();
        $cache
            ->expects(self::exactly(2))
            ->method('delete')
            ->willReturnMap(
                [
                    ['fiz', true],
                    ['biz', true],
                ],
            );

        $result = $cache->deleteMultiple(['fiz', 'biz']);

        self::assertTrue($result);
    }

    public function testDeleteMultipleFailure(): void
    {
        $cache = $this
            ->getMockBuilder(AbstractCache::class)
            ->onlyMethods(['get', 'set', 'clear', 'delete', 'has'])
            ->getMock();
        $cache
            ->expects(self::exactly(2))
            ->method('delete')
            ->willReturnMap(
                [
                    ['fiz', true],
                    ['biz', false],
                ],
            );

        $result = $cache->deleteMultiple(['fiz', 'biz', 'miz']);

        self::assertFalse($result);
    }
}
