<?php
declare(strict_types=1);

namespace LessCache\Redis;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Redis;
use RedisException;

final class RedisCacheFactory
{
    /**
     * @psalm-suppress InvalidArgument invalid stub
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     */
    public function __invoke(ContainerInterface $container): RedisCache
    {
        $config = $container->get('config');
        assert(is_array($config), 'Config needs to be an array');

        $settings = $config[RedisCache::class];
        assert(is_array($settings), 'Settings needs to be an array');

        $host = $settings['host'];
        assert(is_string($host), 'Host setting needs to be set as a string');

        $keyPrefix = $settings['keyPrefix'];
        assert(is_string($keyPrefix), 'Expected string for key prefix setting');

        $redis = new Redis();
        $redis->connect($host);
        $redis->setOption(Redis::OPT_PREFIX, $keyPrefix);

        return new RedisCache($redis);
    }
}
