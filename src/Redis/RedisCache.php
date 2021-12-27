<?php
declare(strict_types=1);

namespace LessCache\Redis;

use DateInterval;
use DateTimeImmutable;
use LessCache\AbstractCache;
use Redis;

final class RedisCache extends AbstractCache
{
    public function __construct(private Redis $redis)
    {}

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->redis->get($key);

        if ($value === false) {
            return $default;
        }

        return unserialize($value);
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        if (isset($ttl)) {
            if ($ttl instanceof DateInterval) {
                $now = new DateTimeImmutable();
                $expires = $now->add($ttl);

                $ttl = $expires->getTimestamp() - $now->getTimestamp();
            }

            return $this->redis->setEx($key, $ttl, serialize($value));
        }

        return $this->redis->set($key, serialize($value));
    }

    public function delete(string $key): bool
    {
        $this->redis->del($key);

        return true;
    }

    public function clear(): bool
    {
        return $this->redis->flushDB();
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($key) === 1;
    }
}
