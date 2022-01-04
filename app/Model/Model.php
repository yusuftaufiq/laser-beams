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

abstract class Model extends BaseModel
{
    abstract public function getTableName(): string;

    public function all(mixed $value = null, string $column = null): ?array
    {
        return $this->select($this->getTableName(), '*', array_merge(
            ['deleted_at' => null],
            $value !== null ? [$column => $value] : []
        ));
    }

    public function own(int $id): bool
    {
        return $this->has($this->getTableName(), ['id' => $id]);
    }

    public function find(int $id): ?array
    {
        return $this->get($this->getTableName(), '*', ['id' => $id, 'deleted_at' => null]);
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
        return $this->change($id, [
            'deleted_at' => $this->raw('now()'),
        ]);
    }
}
