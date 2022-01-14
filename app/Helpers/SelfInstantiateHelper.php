<?php

declare(strict_types=1);

namespace App\Helpers;

trait SelfInstantiateHelper
{
    public static function instantiate(mixed ...$args): static
    {
        return new static(...$args);
    }
}
