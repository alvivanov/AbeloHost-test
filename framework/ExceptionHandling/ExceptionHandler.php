<?php

declare(strict_types=1);

namespace Framework\ExceptionHandling;

use Closure;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use ReflectionFunction;
use ReflectionNamedType;
use Throwable;

final class ExceptionHandler implements ExceptionHandlerInterface
{
    /** @var list<array{0: class-string<Throwable>, 1: Closure}> */
    private array $renderCallbacks = [];

    /** @var list<class-string<Throwable>> */
    private array $dontReport = [];

    private ?Closure $fallback = null;

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function renderable(callable $callback): self
    {
        $this->renderCallbacks[] = [$this->firstParameterType($callback), Closure::fromCallable($callback)];

        return $this;
    }

    private function firstParameterType(callable $callback): string
    {
        $parameters = new ReflectionFunction(Closure::fromCallable($callback))->getParameters();
        $type = $parameters[0]?->getType();

        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            throw new InvalidArgumentException(
                'Reportable/renderable callback must type-hint a Throwable subclass as its first parameter.',
            );
        }

        return $type->getName();
    }

    public function dontReport(string $exceptionClass): self
    {
        $this->dontReport[] = $exceptionClass;

        return $this;
    }

    public function fallback(callable $callback): self
    {
        $this->fallback = Closure::fromCallable($callback);

        return $this;
    }

    public function handle(Throwable $exception): ResponseInterface
    {
        $this->report($exception);

        return $this->render($exception);
    }

    private function report(Throwable $exception): void
    {
        if ($this->shouldntReport($exception)) {
            return;
        }

        $this->logger->error($exception->getMessage(), ['exception' => $exception]);
    }

    private function shouldntReport(Throwable $exception): bool
    {
        return array_any(
            $this->dontReport,
            static fn(string $exceptionClass): bool => $exception instanceof $exceptionClass
        );
    }

    /**
     * @throws Throwable
     */
    private function render(Throwable $exception): ResponseInterface
    {
        foreach ($this->renderCallbacks as [$exceptionClass, $callback]) {
            if ($exception instanceof $exceptionClass) {
                return $callback($exception);
            }
        }

        if ($this->fallback !== null) {
            return ($this->fallback)($exception);
        }

        throw $exception;
    }
}
