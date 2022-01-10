<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

namespace App\Listeners;

use Simps\DB\PDO;
use Simps\Singleton;
use Swoole\Http\Server;

final class Pool
{
    use Singleton;

    final public function workerStart(Server $server, int $workerId): void
    {
        $config = config('database', []);
        if (! empty($config)) {
            PDO::getInstance($config);
        }
    }
}
