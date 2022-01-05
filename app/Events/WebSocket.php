<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

namespace App\Events;

use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

final class WebSocket
{
    final public static function onOpen(Server $server, Request $request): void
    {
        echo "server: handshake success with fd{$request->fd}\n";
    }

    final public static function onMessage(Server $server, Frame $frame): void
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, 'this is server');
    }

    final public static function onClose(Server $server, int $fd): void
    {
        # code...
    }
}
