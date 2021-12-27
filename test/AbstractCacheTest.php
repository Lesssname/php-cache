<?php
declare(strict_types=1);

namespace LessCacheTest;

use LessCache\AbstractCache;
use PHPUnit\Framework\TestCase;
use Traversable;

/**
 * @covers \LessCache\AbstractCache
 */
final class AbstractCacheTest extends TestCase
{
    public function testGetMultiple(): void
    {
        $cache = $this->getMockForAbstractClass(AbstractCache::class);
        $cache
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(
                ['fiz', 'foo'],
                ['biz', 'foo'],
            )
            ->willReturnOnConsecutiveCalls('foo', 'foo');

        $results = $cache->getMultiple(
            ['fiz', 'biz'],
            'foo',
        );

        $results = $results instanceof Traversable
            ? iterator_to_array($results)
            : $results;

        self::assertSame(
            [
                'fiz' => 'foo',
                'biz' => 'foo',
            ],
            $results,
        );
    }

    public function testSetMultiple(): void
    {
        $cache = $this->getMockForAbstractClass(AbstractCache::class);
        $cache
            ->expects(self::exactly(2))
            ->method('set')
            ->withConsecutive(
                ['fiz', 'foo', 123],
                ['biz', 'bar', 123],
            )
            ->willReturnOnConsecutiveCalls(true, true);

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
        $cache = $this->getMockForAbstractClass(AbstractCache::class);
        $cache
            ->expects(self::exactly(2))
            ->method('set')
            ->withConsecutive(
                ['fiz', 'foo', 123],
                ['biz', 'bar', 123],
            )
            ->willReturnOnConsecutiveCalls(true, false);

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
        $cache = $this->getMockForAbstractClass(AbstractCache::class);
        $cache
            ->expects(self::exactly(2))
            ->method('delete')
            ->withConsecutive(['fiz'], ['biz'])
            ->willReturnOnConsecutiveCalls(true, true);

        $result = $cache->deleteMultiple(['fiz', 'biz']);

        self::assertTrue($result);
    }

    public function testDeleteMultipleFailure(): void
    {
        $cache = $this->getMockForAbstractClass(AbstractCache::class);
        $cache
            ->expects(self::exactly(2))
            ->method('delete')
            ->withConsecutive(['fiz'], ['biz'])
            ->willReturnOnConsecutiveCalls(true, false);

        $result = $cache->deleteMultiple(['fiz', 'biz', 'miz']);

        self::assertFalse($result);
    }
}
