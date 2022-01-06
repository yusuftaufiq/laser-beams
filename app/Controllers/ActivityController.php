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
use App\Models\Activity;
use App\Validators\ActivityValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class ActivityController
{
    final public const NOT_FOUND_MESSAGE = 'Activity with ID %d Not Found';

    final public function __construct(
        public readonly Activity $activity = new Activity(),
    ) {
    }

    final public function index(Request $request, Response $response): void
    {
        $result = ResponseHelper::format('Success', 'OK', $this->activity->all());

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $task = $this->activity->find($id);

        $result = match ($task) {
            null => ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id)),
            default => ResponseHelper::format('Success', 'OK', $task),
        };

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function store(Request $request, Response $response): void
    {
        $requestTask = json_decode($request->getContent(), associative: true);
        $violation = ActivityValidator::validateStore($requestTask);

        if ($violation !== null) {
            $result = ResponseHelper::format('Bad Request', $violation);

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_BAD_REQUEST);

            return;
        }

        $id = $this->activity->nextId();
        $task = [...Activity::DEFAULT_COLUMNS_VALUE, ...$requestTask, ...['id' => $id]];

        $result = ResponseHelper::format('Success', 'OK', $task);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_CREATED);

        $this->activity->add($requestTask);
    }

    final public function update(Request $request, Response $response, array $data): void
    {
        $requestTask = json_decode($request->getContent(), associative: true);
        $id = (int) $data['id'];

        $affectedRowsCount = $this->activity->change($id, $requestTask);

        if ($affectedRowsCount === 0) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $task = $this->activity->find($id);

        $result = ResponseHelper::format('Success', 'OK', $task);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        if ($this->activity->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $result = ResponseHelper::format('Success', 'OK');

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);

        $this->activity->remove($id);
    }
}
