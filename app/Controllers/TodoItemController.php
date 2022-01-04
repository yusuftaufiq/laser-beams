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

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function index(Request $request, Response $response): void
    {
        $todo = new TodoItem();
        $id = (int) ($request->get['activity_group_id'] ?? 0);
        $items = match ($id) {
            0, null => $todo->all(),
            default => $todo->all($id, 'activity_group_id'),
        };

        $result = ResponseHelper::format('Success', 'OK', $items);

        return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $todo = new TodoItem();

        if ($todo->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);
        }

        $item = $todo->find($id);

        // Other possibility effective solution
        // if ($item === null) {
        //     # code...
        // }

        $result = ResponseHelper::format('Success', 'OK', $item);

        return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    /**
     * TODO: Return first, then insert into database.
     */
    final public function store(Request $request, Response $response): void
    {
        $requestItem = json_decode($request->getContent(), true);
        $violation = TodoItemValidator::validateStore($requestItem);

        if ($violation !== null) {
            $result = ResponseHelper::format('Bad Request', $violation);

            return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_BAD_REQUEST);
        }

        $todo = new TodoItem();
        $id = $todo->add($requestItem);
        $item = $todo->find($id);
        // $item = array_fill_keys(TodoItem::COLUMNS, null) + $requestItem + ['id' => $id];
        $item['is_active'] = (bool) $item['is_active'];

        $result = ResponseHelper::format('Success', 'OK', $item);

        return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_CREATED);
    }

    /**
     * TODO: Return request->post instead of get item by id from database.
     */
    final public function update(Request $request, Response $response, array $data): void
    {
        $requestItem = json_decode($request->getContent(), true);
        $id = (int) $data['id'];
        $todo = new TodoItem();

        if ($todo->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);
        }

        $affectedRowsCount = $todo->change($id, $requestItem);

        // Other possibility effective solution
        // if ($affectedRowsCount === 0) {
        //     # code...
        // }

        $item = $todo->find($id);
        // $item = array_fill_keys(TodoItem::COLUMNS, null) + $requestItem;
        $item['is_active'] = (bool) $item['is_active'];

        $result = ResponseHelper::format('Success', 'OK', $item);

        return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);

        // $affectedRowsCount = $todo->change($id, $requestItem);
    }

    /**
     * TODO: Return first, then delete from database.
     */
    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $todo = new TodoItem();

        if ($todo->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);
        }

        $affectedRowsCount = $todo->remove($id);

        $result = ResponseHelper::format('Success', 'OK');

        return ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);

        // $affectedRowsCount = $todo->remove($id);
    }
}
