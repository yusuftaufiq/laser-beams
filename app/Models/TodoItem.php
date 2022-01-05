<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

namespace App\Models;

final class TodoItem extends Model
{
    final public const TABLE_NAME = 'todos';

    /**
     * TODO: Remove timestamps?
     */
    final public const COLUMNS = [
        'id',
        'title',
        'activity_group_id',
        'is_active',
        'priority',
        // 'created_at',
        // 'updated_at',
        // 'deleted_at',
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
