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
use App\Repositories\DB\TodoItemRepository;
use App\Repositories\TodoItemRepositoryInterface;
use App\Validators\TodoItemValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class TodoItemController
{
    final private const NOT_FOUND_MESSAGE = 'Todo with ID %d not found';

    final public function __construct(
        private readonly TodoItemRepositoryInterface $todo = new TodoItemRepository(),
    ) {
    }

    #[Route('GET', '/todo-items')]
    final public function index(Request $request, Response $response): void
    {
        $id = (int) ($request->get['activity_group_id'] ?? 0);
        $items = match ($id) {
            0, null => $this->todo->all(),
            default => $this->todo->all($id, 'activity_group_id'),
        };

        ResponseHelper::success(message: 'Successfully retrieve todo items', data: ['items' => $items])
            ->send($response);
    }

    #[Route('GET', '/todo-items/{id:\d+}')]
    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $item = $this->todo->find($id);

        if ($item === null) {
            ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id))->send($response);
            return;
        }

        ResponseHelper::success(message: 'Successfully retrieve todo item', data: ['item' => $item])
            ->send($response);
    }

    #[Route('POST', '/todo-items')]
    final public function store(Request $request, Response $response): void
    {
        $requestItem = json_decode($request->getContent(), associative: true);
        $violations = TodoItemValidator::validate()->store($requestItem);

        if ($violations !== null) {
            ResponseHelper::badRequest($violations)->send($response);
            return;
        }

        $id = $this->todo->nextId();
        $item = [...TodoItemRepository::DEFAULT_COLUMNS_VALUE, ...$requestItem, ...['id' => $id]];
        $item['is_active'] = (bool) $item['is_active'];

        ResponseHelper::success(message: 'Successfully created todo item', data: ['item' => $item])
            ->send($response);

        $this->todo->add($requestItem);
    }

    #[Route('PATCH', '/todo-items/{id:\d+}')]
    final public function update(Request $request, Response $response, array $data): void
    {
        $requestItem = json_decode($request->getContent(), associative: true);
        $violations = TodoItemValidator::validate()->update($requestItem);

        if ($violations !== null) {
            ResponseHelper::badRequest($violations)->send($response);
            return;
        }

        $id = (int) $data['id'];
        $affectedRowsCount = $this->todo->change($id, $requestItem);

        if ($affectedRowsCount === 0) {
            ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id))->send($response);
            return;
        }

        $item = $this->todo->find($id);
        $item['is_active'] = (bool) $item['is_active'];

        ResponseHelper::success(message: 'Successfully updated todo item', data: ['item' => $item])
            ->send(($response));
    }

    #[Route('DELETE', '/todo-items/{id:\d+}')]
    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        if ($this->todo->own($id) === false) {
            ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id))->send($response);
            return;
        }

        ResponseHelper::success(message: 'Successfully deleted todo item')->send($response);

        $this->todo->remove($id);
    }
}
