<?php

declare(strict_types=1);

namespace App\Helpers\Attributes;

#[\Attribute]
final class RouteHelper
{
    final public function __construct(
        private string $httpMethod,
        private string $routePattern,
        private ?string $class = null,
        private ?string $method = null,
    ) {
    }

    final public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    final public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    final public function toArray(): array
    {
        return [$this->httpMethod, $this->routePattern, $this->class . '@' . $this->method];
    }
}
