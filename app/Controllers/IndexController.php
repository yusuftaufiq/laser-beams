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

use App\Helpers\ResponseHelper;
use App\Helpers\StatusCodeHelper;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class IndexController
{
    final public function index(Request $request, Response $response): void
    {
        $result = ResponseHelper::format('Success', 'OK');

        return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }
}
