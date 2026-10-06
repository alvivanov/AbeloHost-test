<?php

declare(strict_types=1);

namespace Framework\Routing;

use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LeagueRouterInterfaceAdapter implements RouterInterface
{
    public function __construct(private readonly Router $router)
    {
    }

    public function get(string $path, callable|array|string $handler, array $middlewares = []): void
    {
        $this->router->get($path, $handler)->middlewares($middlewares);
    }

    public function post(string $path, callable|array|string $handler, array $middlewares = []): void
    {
        $this->router->post($path, $handler)->middlewares($middlewares);
    }

    public function put(string $path, callable|array|string $handler, array $middlewares = []): void
    {
        $this->router->put($path, $handler)->middlewares($middlewares);
    }

    public function patch(string $path, callable|array|string $handler, array $middlewares = []): void
    {
        $this->router->patch($path, $handler)->middlewares($middlewares);
    }

    public function delete(string $path, callable|array|string $handler, array $middlewares = []): void
    {
        $this->router->delete($path, $handler)->middlewares($middlewares);
    }

    public function globalMiddleware(array $middlewares): void
    {
        $this->router->middlewares($middlewares);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->handle($request);
    }
}
