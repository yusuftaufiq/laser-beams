<?php

/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

declare(strict_types=1);

namespace App\Model;

final class Activity extends Model
{
    final public const TABLE_NAME = 'activities';

    final public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    final public function all(mixed $value = null, string $column = null): ?array
    {
        return $this->select($this->getTableName(), [
            'id',
            'email',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ], array_merge(['deleted_at' => null], $value !== null ? [$column => $value] : []));
    }

    final public function find(int $id): ?array
    {
        return $this->get($this->getTableName(), [
            'id',
            'email',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ], ['id' => $id, 'deleted_at' => null]);
    }
}
