<?php

declare(strict_types=1);

namespace Tests;

trait FakerTrait
{
    private \Faker\Generator $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function tearDown()
    {
        unset($this->faker);
    }
}
