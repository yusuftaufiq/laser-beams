<?php

declare(strict_types=1);

namespace App\Validators;

final class TodoItemValidator
{
    final public static function validateStore(array $data): bool|string
    {
        if (
            array_key_exists('activity_group_id', $data) === false
            || is_int($data['activity_group_id']) === false
        ) {
            return 'activity_group_id cannot be null';
        }

        if (
            array_key_exists('title', $data) === false
            || is_int($data['title']) === false
        ) {
            return 'title cannot be null';
        }

        return true;
    }
}
