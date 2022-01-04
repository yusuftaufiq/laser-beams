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
use App\Validators\ActivityValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class ActivityController
{
    final public const NOT_FOUND_MESSAGE = 'Activity with ID %d Not Found';

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function index(Request $request, Response $response): void
    {
        $activity = new Activity();

        $tasks = $activity->all() ?: [];

        $response->setHeader('Content-Type', 'application/json');
        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($tasks));
    }

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        $activity = new Activity();

        $response->setHeader('Content-Type', 'application/json');

        if ($activity->own($id) === false) {
            $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
            $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

            return;
        }

        $task = $activity->find($id);

        // Other possibility effective solution
        // if ($task === null) {
        //     $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
        //     $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

        //     return;
        // }

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($task));
    }

    /**
     * TODO: Return first, then insert into database.
     */
    final public function store(Request $request, Response $response): void
    {
        $requestTask = json_decode($request->getContent(), true);

        $violation = ActivityValidator::validateStore($requestTask);

        $response->setHeader('Content-Type', 'application/json');

        if ($violation !== null) {
            $response->setStatusCode(ResponseHelper::HTTP_BAD_REQUEST);
            $response->end(ResponseHelper::badRequest($violation));

            return;
        }

        $activity = new Activity();

        $id = $activity->add($requestTask);
        $task = $activity->find($id);
        // $task = array_fill_keys(Activity::COLUMNS, null) + $requestTask + ['id' => $id];

        $response->setStatusCode(ResponseHelper::HTTP_CREATED);
        $response->end(ResponseHelper::success($task));
    }

    /**
     * TODO: Return request->post instead of get item by id from database.
     */
    final public function update(Request $request, Response $response, array $data): void
    {
        $requestTask = json_decode($request->getContent(), true);

        $id = (int) $data['id'];

        $activity = new Activity();

        $response->setHeader('Content-Type', 'application/json');

        if ($activity->own($id) === false) {
            $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
            $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

            return;
        }

        $affectedRowsCount = $activity->change($id, $requestTask);

        // Other possibility effective solution
        // if ($affectedRowsCount === 0) {
        //     $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
        //     $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

        //     return;
        // }

        $task = $activity->find($id);
        // $task = array_fill_keys(Activity::COLUMNS, null) + $requestTask;

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success($task));

        // $affectedRowsCount = $activity->change($id, $requestTask);
    }

    /**
     * TODO: Return first, then delete from database.
     */
    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        $activity = new Activity();

        $response->setHeader('Content-Type', 'application/json');

        if ($activity->own($id) === false) {
            $response->setStatusCode(ResponseHelper::HTTP_NOT_FOUND);
            $response->end(ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id)));

            return;
        }

        $affectedRowsCount = $activity->remove($id);

        $response->setStatusCode(ResponseHelper::HTTP_OK);
        $response->end(ResponseHelper::success((object)[]));

        // $affectedRowsCount = $activity->remove($id);
    }
}
