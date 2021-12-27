<?php
declare(strict_types=1);

namespace LessCache;

use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

abstract class AbstractCache implements CacheInterface
{
    /**
     * @param iterable<string> $keys
     * @param mixed $default
     *
     * @return iterable<string, mixed>
     *
     * @throws InvalidArgumentException
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->get($key, $default);
        }
    }

    /**
     * @param iterable<string, mixed> $values
     * @param int|DateInterval|null $ttl
     *
     * @throws InvalidArgumentException
     *
     * @psalm-suppress MixedAssignment unknown values
     * @psalm-suppress MoreSpecificImplementedParamType $values keys need to be strings
     */
    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param iterable<string> $keys
     *
     * @throws InvalidArgumentException
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                return false;
            }
        }

        return true;
    }
}
