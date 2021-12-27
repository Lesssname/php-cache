<?php
declare(strict_types=1);

namespace LessCache\Redis;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Redis;

final class RedisCacheFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RedisCache
    {
        $redis = $container->get(Redis::class);
        assert($redis instanceof Redis, 'Redis needs redis instance');

        return new RedisCache($redis);
    }
}
