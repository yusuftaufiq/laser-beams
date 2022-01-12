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

use App\Repositories\BaseRepositoryInterface;
use Simps\DB\BaseModel;

abstract class BaseRepository extends BaseModel implements BaseRepositoryInterface
{
    public const USE_SOFT_DELETES = false;

    abstract public function getTableName(): string;

    abstract public function getColumns(): array;

    public function all(null|string|int|bool $value = null, string $column = null): array
    {
        return $this->select(
            $this->getTableName(),
            $this->getColumns(),
            [
                ...($value !== null && $column !== null ? [$column => $value] : []),
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
        return $this->activity->action(fn () => (int) $this->update($this->getTableName(), $values, [
            'id' => $id,
        ])->rowCount() ?: false) ?: 0;
    }

    public function remove(int $id): int
    {
        return match (static::USE_SOFT_DELETES) {
            true => $this->change($id, ['deleted_at' => $this->raw('now()')]),
            default => $this->delete($this->getTableName(), ['id' => $id])->rowCount(),
        };
    }

    public function nextId(): int
    {
        return (int) $this->query(
            'SELECT
                <auto_increment>
            FROM
                <information_schema>.<tables>
            WHERE
                <table_name> = :table_name
            LIMIT 1',
            [
                ':table_name' => $this->getTableName(),
            ]
        )->fetch(\PDO::FETCH_OBJ)
        ?->auto_increment;
    }
}