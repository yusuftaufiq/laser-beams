<?php

declare(strict_types=1);

namespace App\Helpers;

use Redis;
use Simps\DB\BaseRedis;
use Swoole\Http\Request;

trait RedisTrait
{
    /**
     * @var \Redis
     */
    protected BaseRedis $redis;

    final public function setRedis(BaseRedis $baseRedis = new BaseRedis()): void
    {
        $this->redis = $baseRedis;

        return $this;
    }

    final public function cache(Request $request, \Closure $fn): mixed
    {
        $this->redis ?? $this->setRedis();

        $key = "{$request->server['request_method']}:{$request->server['path_info']}";
        $cache = $this->redis->get($key);

        if ($cache !== false) {
            return $cache;
        };

        $result = $fn();
        $this->redis->set($key, $result);

        return $result;
    }
}
