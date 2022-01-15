<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Attributes\RouteHelper as Route;
use App\Helpers\Http\ResponseHelper;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class IndexController
{
    #[Route('GET', '/')]
    final public function index(Request $request, Response $response): void
    {
        ResponseHelper::success(message: 'Welcome to simple todo apps built using Swoole')
            ->send($response);
    }
}
