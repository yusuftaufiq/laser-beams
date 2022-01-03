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

use App\Model\TodoItem;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class TodoItemController
{
    final public const NOT_FOUND_MESSAGE = 'Todo with ID %d Not Found';

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
    }

    final public function show(Request $request, Response $response, int $id): void
    {
        $todo = new TodoItem();

        $item = $todo->find($id);

        if ($item === false) {
            $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
            $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));
        }

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($item));
    }
}
