<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

namespace App\Repositories\DB;

use App\Repositories\TodoItemRepositoryInterface;

final class TodoItemRepository extends BaseRepository implements TodoItemRepositoryInterface
{
    final public const TABLE_NAME = 'todo_items';

    final public const COLUMNS = [
        'id',
        'title',
        'activity_group_id',
        'is_active',
        'priority',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    final public const PRIORITY = [
        'very-low',
        'low',
        'high',
        'very-high',
    ];

    final public const DEFAULT_COLUMNS_VALUE = [
        'is_active' => 1,
        'priority' => 'very-high',
    ];

    final public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    final public function getColumns(): array
    {
        return self::COLUMNS;
    }
}
