<?php

declare(strict_types=1);

namespace App\Helpers\Attributes;

use App\Helpers\Attributes\RouteHelper as Route;

final class RouteCollectionHelper
{
    /**
     * @var array<Route> $routes
     */
    private array $routes = [];

    private function getMethodRoutes(\ReflectionMethod $reflectionMethod): array
    {
        $methodRoutes = array_map(
            array: $reflectionMethod->getAttributes(Route::class),
            callback: function (\ReflectionAttribute $attribute) use ($reflectionMethod): object {
                /**
                 * @var Route $route
                 */
                $route = $attribute->newInstance();
                $route->setClass($reflectionMethod->class)->setMethod($reflectionMethod->name);

                return $route;
            },
        );

        return $methodRoutes;
    }

    private function getClassRoutes(\ReflectionClass $reflectionClass): array
    {
        $classRoutes = array_reduce(
            array: $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC),
            callback: fn (array $routes, \ReflectionMethod $method): array => (
                [...$routes, ...$this->getMethodRoutes($method)]
            ),
            initial: [],
        );

        return $classRoutes;
    }

    final public function register(string $class): self
    {
        $reflectionClass = new \ReflectionClass($class);
        $classRoutes = $this->getClassRoutes($reflectionClass);

        $this->routes = [...$this->routes, ...$classRoutes];

        return $this;
    }

    final public function toArray(): array
    {
        return array_map(fn (Route $route) => $route->toArray(), $this->routes);
    }
}
