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
    // use RedisTrait;

    final public const NOT_FOUND_MESSAGE = 'Activity with ID %d Not Found';

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function index(Request $request, Response $response): void
    {
        $activity = new Activity();

        // $result = $this->cache($request, function () {
        //     $activity = new Activity();

            // return ResponseHelper::format('Success', 'OK', $activity->all());
        // });
        $result = ResponseHelper::format('Success', 'OK', $activity->all());

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    /**
     * TODO: Use Memcached or use HTTP response cache?
     */
    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $activity = new Activity();

        if ($activity->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $task = $activity->find($id);

        // Other possibility effective solution
        // if ($task === null) {
        //     # code...
        // }

        // $result = $this->cache($request, function () use ($data) {
        //     $id = (int) $data['id'];

        //     $activity = new Activity();
        //     $task = $activity->find($id);

        //     if ($task === null) {
        //         return ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));
        //     }

        //     return ResponseHelper::format('Success', 'OK', $task);
        // });

        $result = ResponseHelper::format('Success', 'OK', $task);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    /**
     * TODO: Return first, then insert into database.
     */
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
        $id = $activity->add($requestTask);
        $task = $activity->find($id);
        // $task = array_fill_keys(Activity::COLUMNS, null) + $requestTask + ['id' => $id];

        $result = ResponseHelper::format('Success', 'OK', $task);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_CREATED);
    }

    /**
     * TODO: Return request->post instead of get item by id from database.
     */
    final public function update(Request $request, Response $response, array $data): void
    {
        $requestTask = json_decode($request->getContent(), true);
        $id = (int) $data['id'];
        $activity = new Activity();

        if ($activity->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $affectedRowsCount = $activity->change($id, $requestTask);

        // Other possibility effective solution
        // if ($affectedRowsCount === 0) {
        //     # code...
        // }

        $task = $activity->find($id);
        // $task = array_fill_keys(Activity::COLUMNS, null) + $requestTask;

        $result = ResponseHelper::format('Success', 'OK', $task);

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);

        // $affectedRowsCount = $activity->change($id, $requestTask);
    }

    /**
     * TODO: Return first, then delete from database.
     */
    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $activity = new Activity();

        if ($activity->own($id) === false) {
            $result = ResponseHelper::format('Not Found', sprintf(self::NOT_FOUND_MESSAGE, $id));

            ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);

            return;
        }

        $affectedRowsCount = $activity->remove($id);

        $result = ResponseHelper::format('Success', 'OK');

        ResponseHelper::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);

        // $affectedRowsCount = $activity->remove($id);
    }
}
