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

final class Activity extends Model
{
    final public const TABLE_NAME = 'activities';

    final public const COLUMNS = [
        'id',
        'email',
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
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
