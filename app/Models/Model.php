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

use Simps\DB\BaseModel;

abstract class Model extends BaseModel
{
    public const USE_SOFT_DELETES = false;

    abstract public function getTableName(): string;

    abstract public function getColumns(): array;

    public function all(mixed $value = null, string $column = null): array
    {
        return $this->select(
            $this->getTableName(),
            $this->getColumns(),
            [
                ...($value !== null ? [$column => $value] : []),
                ...(static::USE_SOFT_DELETES ? ['deleted_at' => null] : []),
            ],
        ) ?: [];
    }

    public function own(int $id): bool
    {
        return $this->has($this->getTableName(), ['id' => $id]);
    }

    public function find(int $id): ?array
    {
        return $this->get(
            $this->getTableName(),
            $this->getColumns(),
            [
                'id' => $id,
                ...(static::USE_SOFT_DELETES ? ['deleted_at' => null] : []),
            ],
        ) ?: null;
    }

    public function add(array $values): int
    {
        return (int) $this->insert($this->getTableName(), $values);
    }

    public function change(int $id, array $values): int
    {
        return $this->update($this->getTableName(), $values, [
            'id' => $id,
        ])->rowCount();
    }

    public function remove(int $id): int
    {
        return match (static::USE_SOFT_DELETES) {
            true => $this->change($id, ['deleted_at' => $this->raw('now()')]),
            default => $this->delete($this->getTableName(), ['id' => $id])->rowCount(),
        };
    }
}
