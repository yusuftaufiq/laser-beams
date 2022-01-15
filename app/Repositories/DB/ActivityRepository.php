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

use App\Repositories\ActivityRepositoryInterface;

final class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
    final protected const USE_SOFT_DELETES = true;

    final private const TABLE_NAME = 'activities';

    final private const COLUMNS = [
        'id',
        'email',
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    final public const DEFAULT_COLUMNS_VALUE = [
        'email' => null,
    ];

    final public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    final public static function getColumns(): array
    {
        return self::COLUMNS;
    }
}
