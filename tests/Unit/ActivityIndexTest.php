<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\ActivityController;
use App\Repositories\ActivityRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\ActivityTrait;
use Tests\FakerTrait;

/**
 * @method \PHPUnit\Framework\MockObject\MockObject createMock
 */
class ActivityIndexTest extends TestCase
{
    use ActivityTrait;
    use FakerTrait;

    public function setUp(): void
    {
        $this->setUpFaker();
    }

    public function tearDown(): void
    {
        $this->tearDownFaker();
    }

    public function testShowAllActivities(): void
    {
        $expect = $this->generateActivities(count: $this->faker->randomDigitNotZero());

        $activityRepositoryMock = $this
            ->getMockBuilder(ActivityRepositoryInterface::class)
            ->onlyMethods(['all'])
            ->disableOriginalConstructor()
            ->getMock();
        $activityRepositoryMock
            ->expects($this->once())
            ->method('all')
            ->willReturn($expect);

        $requestMock = $this->createMock(\Swoole\Http\Request::class);

        $responseMock = $this->createMock(\Swoole\Http\Response::class);
        $responseMock
            ->expects($this->exactly(1))
            ->method('setHeader')
            ->withConsecutive(['Content-Type', 'application/json']);
        $responseMock
            ->expects($this->once())
            ->method('setStatusCode')
            ->with(200);
        $responseMock
            ->expects($this->once())
            ->method('end')
            ->with(json_encode([
                'status' => 'Success',
                'message' => 'OK',
                'data' => $expect,
            ]));

        /**
         * @var \App\Repositories\ActivityRepositoryInterface $activityRepositoryMock
         * @var \Swoole\Http\Request                          $requestMock
         * @var \Swoole\Http\Response                         $responseMock
         */
        $activity = new ActivityController($activityRepositoryMock);
        $activity->index($requestMock, $responseMock);
    }
}
