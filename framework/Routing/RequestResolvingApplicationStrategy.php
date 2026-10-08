<?php

declare(strict_types=1);

namespace Framework\Routing;

use League\Route\Route;
use League\Route\Strategy\ApplicationStrategy;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;

final class RequestResolvingApplicationStrategy extends ApplicationStrategy
{
    #[Override]
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        $routeVars = $route->getVars();

        foreach ($routeVars as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $container = $this->getContainer();
        $controller = $route->getCallable($container);
        $arguments = $this->resolveArguments($controller, $request, $routeVars);

        return $this->decorateResponse($controller(...$arguments));
    }

    private function resolveArguments(callable $controller, ServerRequestInterface $request, array $routeVars): array
    {
        $reflection = is_array($controller)
            ? new ReflectionMethod($controller[0], $controller[1])
            : new ReflectionFunction($controller);

        $container = $this->getContainer();
        $arguments = [];

        foreach ($reflection->getParameters() as $parameter) {
            $type = $parameter->getType();
            $typeName = $type instanceof ReflectionNamedType && !$type->isBuiltin() ? $type->getName() : null;

            $arguments[] = match (true) {
                $typeName === null => $routeVars,
                $request instanceof $typeName => $request,
                $this->wrapsRequest($typeName, $request) => new $typeName($request),
                default => $container?->get($typeName),
            };
        }

        return $arguments;
    }

    private function wrapsRequest(string $class, ServerRequestInterface $request): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        $constructor = new ReflectionClass($class)->getConstructor();

        if ($constructor === null || $constructor->getNumberOfParameters() !== 1) {
            return false;
        }

        $paramType = $constructor->getParameters()[0]->getType();
        $paramTypeName = $paramType instanceof ReflectionNamedType && !$paramType->isBuiltin()
            ? $paramType->getName()
            : null;

        return $paramTypeName !== null && $request instanceof $paramTypeName;
    }
}
