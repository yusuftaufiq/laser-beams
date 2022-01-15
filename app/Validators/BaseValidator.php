<?php

declare(strict_types=1);

namespace App\Validators;

use App\Helpers\SelfInstantiateHelper;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseValidator
{
    use SelfInstantiateHelper {
        SelfInstantiateHelper::instantiate as validate;
    }

    final public function __construct(
        protected ValidatorInterface $validator,
    ) {
        $this->validator = Validation::createValidator();
    }

    abstract public function store(array $input): ?ConstraintViolationListInterface;

    abstract public function update(array $input): ?ConstraintViolationListInterface;
}
