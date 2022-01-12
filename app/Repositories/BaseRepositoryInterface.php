<?php

declare(strict_types=1);

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function all(null|string|int|bool $value = null, string $column = null): array;

    public function own(int $id): bool;

    public function find(int $id): ?array;

    public function add(array $values): int;

    public function change(int $id, array $values): int;

    public function remove(int $id): int;

    public function nextId(): int;
}
