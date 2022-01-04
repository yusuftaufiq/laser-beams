<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

use App\Controllers\ActivityController;
use App\Controllers\IndexController;
use App\Controllers\TodoItemController;

return [
    ['GET', '/', IndexController::class . '@' . 'index'],
    ['GET', '/todo-items', TodoItemController::class  . '@' . 'index'],
    ['GET', '/todo-items/{id:\d+}', TodoItemController::class  . '@' . 'show'],
    ['POST', '/todo-items', TodoItemController::class  . '@' . 'store'],
    ['PATCH', '/todo-items/{id:\d+}', TodoItemController::class  . '@' . 'update'],
    ['DELETE', '/todo-items/{id:\d+}', TodoItemController::class  . '@' . 'destroy'],
    ['GET', '/activity-groups', ActivityController::class  . '@' . 'index'],
    ['GET', '/activity-groups/{id:\d+}', ActivityController::class  . '@' . 'show'],
    ['POST', '/activity-groups', ActivityController::class  . '@' . 'store'],
    ['PATCH', '/activity-groups/{id:\d+}', ActivityController::class  . '@' . 'update'],
    ['DELETE', '/activity-groups/{id:\d+}', ActivityController::class  . '@' . 'destroy'],
];
