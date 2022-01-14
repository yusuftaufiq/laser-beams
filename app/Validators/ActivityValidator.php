<?php

declare(strict_types=1);

namespace App\Validators;

use App\Helpers\SelfInstantiateHelper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ActivityValidator
{
    use SelfInstantiateHelper {
        SelfInstantiateHelper::instantiate as validate;
    }

    private ValidatorInterface $validator;

    final public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    final public function store(array $input): ?ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection([
            'title' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\NotNull(),
            ]),
            'email' => new Assert\Optional([
                new Assert\Email(),
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
            'title' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\NotNull(),
            ]),
            'email' => new Assert\Optional([
                new Assert\Email(),
            ]),
        ]);

        $violations = $this->validator->validate($input, $constraint);

        if ($violations->count() > 0) {
            return new ConstraintViolationList($violations);
        }

        return null;
    }
}
