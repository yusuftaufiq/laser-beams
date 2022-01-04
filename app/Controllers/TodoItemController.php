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
use App\Models\TodoItem;
use App\Validators\TodoItemValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class TodoItemController
{
    final public const NOT_FOUND_MESSAGE = 'Todo with ID %d Not Found';

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function index(Request $request, Response $response): void
    {
        $todo = new TodoItem();

        $id = (int) ($request->get['activity_group_id'] ?? 0);

        $items = match ($id) {
            0, null => $todo->all() ?: [],
            default => $todo->all($id, 'activity_group_id') ?: [],
        };

        $response->setHeader('Content-Type', 'application/json');
        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($items));
    }

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        $todo = new TodoItem();

        $response->setHeader('Content-Type', 'application/json');

        if ($todo->own($id) === false) {
            $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
            $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

            return;
        }

        $item = $todo->find($id);

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($item));
    }

    /**
     * TODO: Return first, then insert into database.
     */
    final public function store(Request $request, Response $response): void
    {
        $requestItem = json_decode($request->getContent(), true);

        $violation = TodoItemValidator::validateStore($requestItem);

        $response->setHeader('Content-Type', 'application/json');

        if ($violation !== null) {
            $response->setStatusCode(ResponseHelper::HTTP_BAD_REQUEST);
            $response->end(ResponseHelper::badRequest($violation));

            return;
        }

        $todo = new TodoItem();

        $id = $todo->add($requestItem);
        $item = $todo->find($id);
        $item['is_active'] = (bool) $item['is_active'];

        $response->setStatusCode(ResponseHelper::HTTP_CREATED);
        $response->end(ResponseHelper::success($item));
    }

    /**
     * TODO: Return request->post instead of get item by id from database.
     */
    final public function update(Request $request, Response $response, array $data): void
    {
        $requestItem = json_decode($request->getContent(), true);

        $id = (int) $data['id'];

        $todo = new TodoItem();

        $response->setHeader('Content-Type', 'application/json');

        if ($todo->own($id) === false) {
            $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
            $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

            return;
        }

        $affectedRowsCount = $todo->change($id, $requestItem);
        $item = $todo->find($id);
        $item['is_active'] = (bool) $item['is_active'];

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($item));
    }

    /**
     * TODO: Return first, then delete from database.
     */
    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        $todo = new TodoItem();

        $response->setHeader('Content-Type', 'application/json');

        if ($todo->own($id) === false) {
            $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
            $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

            return;
        }

        $affectedRowsCount = $todo->remove($id);

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success((object)[]));
    }
}
