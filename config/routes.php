<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

use App\Controllers\IndexController;
use App\Controllers\TodoItemController;

return [
    ['GET', '/', IndexController::class . '@' . 'index'],
    ['GET', '/todo-items', TodoItemController::class  . '@' . 'index'],
    ['GET', '/todo-items/[{id:\d+}]', TodoItemController::class  . '@' . 'show'],
];
