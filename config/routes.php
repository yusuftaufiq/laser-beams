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
use App\Helpers\Attributes\RouteCollectionHelper as RouteCollection;

$routes = new RouteCollection();

$routes->register(IndexController::class);
$routes->register(ActivityController::class);
$routes->register(TodoItemController::class);

return $routes->toArray();
