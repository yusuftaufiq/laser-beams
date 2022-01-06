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
use App\Models\TodoItem;
use App\Validators\TodoItemValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class TodoItemController
{
    final public const NOT_FOUND_MESSAGE = 'Todo with ID %d Not Found';

    final public function index(Request $request, Response $response): void
    {
        $todo = new TodoItem();
        $id = (int) ($request->get['activity_group_id'] ?? 0);
        $items = match ($id) {
            0, null => $todo->all(),
            default => $todo->all($id, 'activity_group_id'),
        };

        $result = ResponseHelper::format('Success', 'OK', $items);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $todo = new TodoItem();
        $item = $todo->find($id);

        if ($item === null) {
            ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            return;
        }

        $result =  ResponseHelper::format('Success', 'OK', $item);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function store(Request $request, Response $response): void
    {
        $requestItem = json_decode($request->getContent(), true);
        $violation = TodoItemValidator::validateStore($requestItem);

        if ($violation !== null) {
            $result = ResponseHelper::format('Bad Request', $violation);

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_BAD_REQUEST);

            return;
        }

        $todo = new TodoItem();
        $id = $todo->nextId();
        $item = [...TodoItem::DEFAULT_COLUMNS_VALUE, ...$requestItem, ...['id' => $id]];
        $item['is_active'] = (bool) $item['is_active'];

        $result = ResponseHelper::format('Success', 'OK', $item);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_CREATED);

        $todo->add($requestItem);
    }

    final public function update(Request $request, Response $response, array $data): void
    {
        $requestItem = json_decode($request->getContent(), true);

        $id = (int) $data['id'];
        $todo = new TodoItem();

        $affectedRowsCount = $todo->change($id, $requestItem);

        if ($affectedRowsCount === 0) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $item = $todo->find($id);
        $item['is_active'] = (bool) $item['is_active'];

        $result = ResponseHelper::format('Success', 'OK', $item);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $todo = new TodoItem();

        if ($todo->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $result = ResponseHelper::format('Success', 'OK');

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);

        $todo->remove($id);
    }
}
