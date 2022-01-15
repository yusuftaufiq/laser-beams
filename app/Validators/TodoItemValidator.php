<?php

declare(strict_types=1);

namespace App\Validators;

use App\Repositories\DB\TodoItemRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class TodoItemValidator extends BaseValidator
{
    final public function store(array $input): ?ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection([
            'activity_group_id' => new Assert\Required([
                new Assert\Positive(),
            ]),
            'title' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\NotNull(),
            ]),
            'is_active' => new Assert\Optional([
                new Assert\Choice([true, false]),
            ]),
            'priority' => new Assert\Optional([
                new Assert\Choice(TodoItemRepository::PRIORITY),
            ]),
        ]);

        $violations = $this->validator->validate($input, $constraint);

        if ($violations->count() > 0) {
            return new ConstraintViolationList($violations);
        }

        return null;
    }

    final public function update(array $input): ?ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection([
            'activity_group_id' => new Assert\Optional([
                new Assert\Positive(),
            ]),
            'title' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\NotNull(),
            ]),
            'is_active' => new Assert\Optional([
                new Assert\Choice([true, false]),
            ]),
            'priority' => new Assert\Optional([
                new Assert\Choice(TodoItemRepository::PRIORITY),
            ]),
        ]);

        $violations = $this->validator->validate($input, $constraint);

        if ($violations->count() > 0) {
            return new ConstraintViolationList($violations);
        }

        return null;
    }
}
