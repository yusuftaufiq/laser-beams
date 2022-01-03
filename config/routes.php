<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

use App\Controller\IndexController;
use Swoole\Http\Request;
use Swoole\Http\Response;

return [
    ['GET', '/', IndexController::class . '@' . 'index'],
    ['GET', '/hello[/{name}]', IndexController::class  . '@' . 'hello'],
    ['GET', '/favicon.ico', function (Request $request, Response $response) {
        $response->end();
    }],
];
