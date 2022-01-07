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
use App\Models\Activity;
use App\Models\Model;
use App\Validators\ActivityValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class ActivityController
{
    final public const NOT_FOUND_MESSAGE = 'Activity with ID %d Not Found';

    final public function __construct(
        public readonly Model $activity = new Activity(),
    ) {
    }

    final public function index(Request $request, Response $response): void
    {
        ResponseHelper::success($response, $this->activity->all());
    }

    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $task = $this->activity->find($id);

        if ($task === null) {
            ResponseHelper::notFound($response, sprintf(self::NOT_FOUND_MESSAGE, $id));
            return;
        }

        ResponseHelper::success($response, $task);
    }

    final public function store(Request $request, Response $response): void
    {
        $requestTask = json_decode($request->getContent(), associative: true);
        $violation = ActivityValidator::validateStore($requestTask);

        if ($violation !== null) {
            ResponseHelper::badRequest($response, $violation);
            return;
        }

        $id = $this->activity->nextId();
        $task = [...Activity::DEFAULT_COLUMNS_VALUE, ...$requestTask, ...['id' => $id]];

        ResponseHelper::created($response, $task);

        $this->activity->add($requestTask);
    }

    final public function update(Request $request, Response $response, array $data): void
    {
        $requestTask = json_decode($request->getContent(), associative: true);
        $id = (int) $data['id'];

        $affectedRowsCount = $this->activity->action(fn () => $this->activity->change($id, $requestTask) ?: false) ?: 0;

        if ($affectedRowsCount === 0) {
            ResponseHelper::notFound($response, sprintf(self::NOT_FOUND_MESSAGE, $id));
            return;
        }

        $task = $this->activity->find($id);

        ResponseHelper::success($response, $task);
    }

    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        if ($this->activity->own($id) === false) {
            ResponseHelper::notFound($response, sprintf(self::NOT_FOUND_MESSAGE, $id));
            return;
        }

        ResponseHelper::success($response);

        $this->activity->remove($id);
    }
}
