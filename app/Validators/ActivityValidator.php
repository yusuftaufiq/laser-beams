<?php

declare(strict_types=1);

namespace App\Validators;

final class ActivityValidator
{
    final public static function validateStore(array $data): ?string
    {
        if (array_key_exists('title', $data) === false) {
            return 'title cannot be null';
        }

        return null;
    }
}
