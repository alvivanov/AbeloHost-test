<?php

declare(strict_types=1);

namespace Framework;

use Dotenv\Dotenv;
use Framework\ExceptionHandling\ExceptionHandlerInterface;
use Framework\Http\Emitter\EmitterInterface;
use Framework\Routing\RouterInterface;
use Framework\ViewFactory\ViewFactoryInterface;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final readonly class Application
{
    private RouterInterface $router;
    private ExceptionHandlerInterface $exceptionHandler;
    private ViewFactoryInterface $viewFactory;
    private EmitterInterface $emitter;

    private function __construct(private ContainerInterface $container)
    {
        $this->router = $this->container->get(RouterInterface::class);
        $this->exceptionHandler = $this->container->get(ExceptionHandlerInterface::class);
        $this->emitter = $this->container->get(EmitterInterface::class);
        $this->viewFactory = $this->container->get(ViewFactoryInterface::class);

        $this->boot();
    }

    public static function create(ContainerInterface $container): self
    {
        return new self($container);
    }

    public function renderResponse(ResponseInterface $response): void
    {
        $this->emitter->emit($response);
    }

    public function handleRequest(?ServerRequestInterface $request = null): ResponseInterface
    {
        $request ??= $this->container->get(ServerRequestCreator::class)->fromGlobals();

        try {
            $response = $this->router->handle($request);
        } catch (Throwable $exception) {
            $response = $this->exceptionHandler->handle($exception);
        }

        return $response;
    }

    private function boot(): void
    {
        $this->initEnv($this->container->get('boot.envFile'));
        $this->bootExceptionHandlers($this->container->get('boot.exceptions'));
        $this->bootRoutes($this->container->get('boot.routes'));
    }

    private function initEnv(string $envFile): void
    {
        Dotenv::createUnsafeMutable(dirname($envFile), basename($envFile))->load();
    }

    private function bootExceptionHandlers(array $exceptions): void
    {
        foreach ($exceptions as $exception) {
            (require $exception)($this->exceptionHandler, $this->viewFactory);
        }

        set_exception_handler(function (Throwable $exception): void {
            try {
                $this->emitter->emit($this->exceptionHandler->handle($exception));
            } catch (Throwable) {
                http_response_code(500);

                echo 'Internal Server Error';
            }
        });
    }

    private function bootRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            (require $route)($this->router);
        }
    }
}
