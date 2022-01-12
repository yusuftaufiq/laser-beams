<?php

declare(strict_types=1);

namespace Tests;

trait ActivityTrait
{
    public function generateActivityId(): int
    {
        return $this->faker->unique()->numberBetween(1, 1000);
    }

    public function generateActivity(): array
    {
        return [
            'id' => $this->generateActivityId(),
            'email' => $this->faker->email(),
            'title' => $this->faker->realText(),
        ];
    }

    public function generateActivities(int $count): array
    {
        return array_map(fn (): array => (
            $this->generateActivity()
        ), array_fill(0, $count, null));
    }

    public function generateNotFoundMessage($id): string
    {
        return sprintf('Activity with ID %d Not Found', $id);
    }
}
