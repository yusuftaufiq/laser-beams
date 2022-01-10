<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\ActivityController;
use App\Models\Model;
use PHPUnit\Framework\TestCase;
use Tests\FakerTrait;

/**
 * @method \PHPUnit\Framework\MockObject\MockObject createMock
 */
class ActivityIndexTest extends TestCase
{
    use FakerTrait;

    public function testIndex(): void
    {
        $expect = array_map(fn (): array => ([
            'id' => $this->faker->numberBetween(1, 100),
            'email' => $this->faker->email(),
            'title' => $this->faker->realText(),
        ]), array_fill(0, $this->faker->randomDigitNotZero(), null));

        $modelMock = $this
            ->getMockBuilder(Model::class)
            ->onlyMethods(['all'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $modelMock
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

        $activity = new ActivityController($modelMock);
        $activity->index($requestMock, $responseMock);
    }
}
