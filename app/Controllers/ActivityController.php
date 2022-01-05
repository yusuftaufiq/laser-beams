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

use App\Helpers\RedisTrait;
use App\Helpers\ResponseHelper;
use App\Helpers\StatusCodeHelper;
use App\Models\Activity;
use App\Validators\ActivityValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class ActivityController
{
    use RedisTrait;

    final public const NOT_FOUND_MESSAGE = 'Activity with ID %d Not Found';

    final public function index(Request $request, Response $response): void
    {
        $result = $this->cache($request, function () {
            $activity = new Activity();

            return ResponseHelper::format('Success', 'OK', $activity->all());
        });

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function show(Request $request, Response $response, array $data): void
    {
        $result = $this->cache($request, function () use ($data) {
            $id = (int) $data['id'];
            $activity = new Activity();
            $task = $activity->find($id);

            if ($task === null) {
                return ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));
            }

            return ResponseHelper::format('Success', 'OK', $task);
        });

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function store(Request $request, Response $response): void
    {
        $requestTask = json_decode($request->getContent(), true);
        $violation = ActivityValidator::validateStore($requestTask);

        if ($violation !== null) {
            $result = ResponseHelper::format('Bad Request', $violation);

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_BAD_REQUEST);

            return;
        }

        $activity = new Activity();
        $id = $activity->nextId();
        $task = [...Activity::DEFAULT_COLUMNS_VALUE, ...$requestTask, ...['id' => $id]];

        $result = ResponseHelper::format('Success', 'OK', $task);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_CREATED);

        $activity->add($requestTask);
    }

    final public function update(Request $request, Response $response, array $data): void
    {
        $requestTask = json_decode($request->getContent(), true);

        $id = (int) $data['id'];
        $activity = new Activity();

        $affectedRowsCount = $activity->change($id, $requestTask);

        if ($affectedRowsCount === 0) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $task = $activity->find($id);

        $result = ResponseHelper::format('Success', 'OK', $task);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $activity = new Activity();

        if ($activity->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $result = ResponseHelper::format('Success', 'OK');

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);

        $activity->remove($id);
    }
}
