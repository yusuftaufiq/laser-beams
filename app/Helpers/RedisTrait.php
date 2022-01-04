<?php

declare(strict_types=1);

namespace App\Helpers;

use Simps\DB\BaseRedis;
use Swoole\Http\Request;

trait RedisTrait
{
    final public function cache(Request $request, \Closure $fn): mixed
    {
        /**
         * @var \Redis
         */
        $redis = new BaseRedis();

        $key = "{$request->server['request_method']}:{$request->server['path_info']}";
        $cache = $redis->get($key);

        if ($cache !== false) {
            return $cache;
        };

        $result = $fn();
        $redis->set($key, $result);

        return $result;
    }
}
