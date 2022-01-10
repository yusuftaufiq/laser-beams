<?php

declare(strict_types=1);

namespace Tests;

trait FakerTrait
{
    private \Faker\Generator $faker;

    public function setUpFaker(): void
    {
        $this->faker = \Faker\Factory::create();
    }

    public function tearDownFaker(): void
    {
        unset($this->faker);
    }
}
