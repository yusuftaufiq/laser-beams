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
use App\Repositories\DB\TodoItemRepository;
use App\Repositories\TodoItemRepositoryInterface;
use App\Validators\TodoItemValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class TodoItemController
{
    final public const NOT_FOUND_MESSAGE = 'Todo with ID %d Not Found';

    final public function __construct(
        public readonly TodoItemRepositoryInterface $todo = new TodoItemRepository(),
    ) {
    }

    final public function index(Request $request, Response $response): void
    {
        $id = (int) ($request->get['activity_group_id'] ?? 0);
        $items = match ($id) {
            0, null => $this->todo->all(),
            default => $this->todo->all($id, 'activity_group_id'),
        };

        ResponseHelper::success($response, $items);
    }

    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $item = $this->todo->find($id);

        if ($item === null) {
            ResponseHelper::notFound($response, sprintf(self::NOT_FOUND_MESSAGE, $id));
            return;
        }

        ResponseHelper::success($response, $item);
    }

    final public function store(Request $request, Response $response): void
    {
        $requestItem = json_decode($request->getContent(), associative: true);
        $violation = TodoItemValidator::validateStore($requestItem);

        if ($violation !== null) {
            ResponseHelper::badRequest($response, $violation);
            return;
        }

        $id = $this->todo->nextId();
        $item = [...TodoItemRepository::DEFAULT_COLUMNS_VALUE, ...$requestItem, ...['id' => $id]];
        $item['is_active'] = (bool) $item['is_active'];

        ResponseHelper::created($response, $item);

        $this->todo->add($requestItem);
    }

    final public function update(Request $request, Response $response, array $data): void
    {
        $requestItem = json_decode($request->getContent(), associative: true);
        $id = (int) $data['id'];

        $affectedRowsCount = $this->todo->change($id, $requestItem);

        if ($affectedRowsCount === 0) {
            ResponseHelper::notFound($response, sprintf(self::NOT_FOUND_MESSAGE, $id));
            return;
        }

        $item = $this->todo->find($id);
        $item['is_active'] = (bool) $item['is_active'];

        ResponseHelper::success($response, $item);
    }

    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        if ($this->todo->own($id) === false) {
            ResponseHelper::notFound($response, sprintf(self::NOT_FOUND_MESSAGE, $id));
            return;
        }

        ResponseHelper::success($response);

        $this->todo->remove($id);
    }
}
