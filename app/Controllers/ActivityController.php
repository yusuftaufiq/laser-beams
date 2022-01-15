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
use App\Repositories\ActivityRepositoryInterface;
use App\Repositories\DB\ActivityRepository;
use App\Validators\ActivityValidator;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class ActivityController
{
    final private const NOT_FOUND_MESSAGE = 'Activity with ID %d Not Found';

    final public function __construct(
        private readonly ActivityRepositoryInterface $activity = new ActivityRepository(),
    ) {
    }

    #[Route('GET', '/activity-groups')]
    final public function index(Request $request, Response $response): void
    {
        $activities = $this->activity->all();

        ResponseHelper::success(message: 'Successfully retrieve activities', data: ['activities' => $activities])
            ->send($response);
    }

    #[Route('GET', '/activity-groups/{id:\d+}')]
    final public function show(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];
        $activity = $this->activity->find($id);

        if ($activity === null) {
            ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id))->send($response);
            return;
        }

        ResponseHelper::success(message: 'Successfully retrieve activity', data: ['activity' => $activity])
            ->send($response);
    }

    #[Route('POST', '/activity-groups')]
    final public function store(Request $request, Response $response): void
    {
        $requestActivity = json_decode($request->getContent(), associative: true);
        $violations = ActivityValidator::validate()->store($requestActivity);

        if ($violations !== null) {
            ResponseHelper::badRequest($violations)->send($response);
            return;
        }

        $id = $this->activity->nextId();
        $activity = [...ActivityRepository::DEFAULT_COLUMNS_VALUE, ...$requestActivity, ...['id' => $id]];

        ResponseHelper::success(message: 'Successfully created activity', data: ['activity' => $activity])
            ->send($response);

        $this->activity->add($requestActivity);
    }

    #[Route('PATCH', '/activity-groups/{id:\d+}')]
    final public function update(Request $request, Response $response, array $data): void
    {
        $requestActivity = json_decode($request->getContent(), associative: true);
        $violations = ActivityValidator::validate()->update($requestActivity);

        if ($violations !== null) {
            ResponseHelper::badRequest($violations)->send($response);
            return;
        }

        $id = (int) $data['id'];
        $affectedRowsCount = $this->activity->change($id, $requestActivity);

        if ($affectedRowsCount === 0) {
            ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id))->send($response);
            return;
        }

        $activity = $this->activity->find($id);

        ResponseHelper::success(message: 'Successfully updated activity', data: ['activity' => $activity])
            ->send($response);
    }

    #[Route('DELETE', '/activity-groups/{id:\d+}')]
    final public function destroy(Request $request, Response $response, array $data): void
    {
        $id = (int) $data['id'];

        if ($this->activity->own($id) === false) {
            ResponseHelper::notFound(sprintf(self::NOT_FOUND_MESSAGE, $id))->send($response);
            return;
        }

        ResponseHelper::success(message: 'Successfully deleted activity')->send($response);

        $this->activity->remove($id);
    }
}
