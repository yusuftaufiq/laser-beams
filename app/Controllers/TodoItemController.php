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

use Swoole\Http\Request;
use Swoole\Http\Response;

final class TodoItemController
{
    final public function index(Request $request, Response $response): void
    {
        $todo = new TodoItem();

        $id = (int) $request->get['activity_group_id'] ?? null;

        $items = match ($id) {
            0, null => $todo->all(),
            default => $todo->find($id, 'activity_group_id'),
        };

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($items));
        # code...
    }
}
