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

use Simps\DB\BaseModel;

final class TodoItem extends BaseModel
{
    final public const TABLE = 'todos';

    final public function all(): array
    {
        return $this->select(self::TABLE, [
            'id',
            'email',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ], ['deleted_at[!]' => null]) ?: [];
    }

    final public function find(mixed $value, string $column = 'id'): array|false
    {
        if (!$this->has(self::TABLE, [$column => $value])) {
            return false;
        }

        return $this->get(self::TABLE, [
            'id',
            'email',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ], [$column => $value, 'deleted_at[!]' => null]);
    }

    final public function add(array $values): int
    {
        $item = $this->insert(self::TABLE, $values);

        return $item->id();
    }

    final public function change(int $id, array $values): int
    {
        return $this->update(self::TABLE, $values, [
            'id' => $id,
        ])->rowCount();
    }

    final public function remove(int $id): int
    {
        return $this->change($id, [
            'deleted_at' => $this->raw('now()'),
        ]);
    }
}
