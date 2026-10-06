<?php

declare(strict_types=1);

namespace Framework\Routing;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface RouterInterface extends RequestHandlerInterface
{
    /** @param MiddlewareInterface[] $middlewares */
    public function get(string $path, callable|array|string $handler, array $middlewares = []): void;

    /** @param MiddlewareInterface[] $middlewares */
    public function post(string $path, callable|array|string $handler, array $middlewares = []): void;

    /** @param MiddlewareInterface[] $middlewares */
    public function put(string $path, callable|array|string $handler, array $middlewares = []): void;

    /** @param MiddlewareInterface[] $middlewares */
    public function patch(string $path, callable|array|string $handler, array $middlewares = []): void;

    /** @param MiddlewareInterface[] $middlewares */
    public function delete(string $path, callable|array|string $handler, array $middlewares = []): void;

    /** @param MiddlewareInterface[] $middlewares */
    public function globalMiddleware(array $middlewares): void;
}
