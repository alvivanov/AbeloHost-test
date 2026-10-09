<?php

declare(strict_types=1);

namespace Framework\ExceptionHandling;

use Throwable;

interface ExceptionHandlerInterface
{
    /** @param callable(Throwable): mixed $callback */
    public function renderable(callable $callback): static;

    /** @param class-string<Throwable> $exceptionClass */
    public function dontReport(string $exceptionClass): static;

    /** @param callable(Throwable): mixed $callback */
    public function fallback(callable $callback): static;

    public function handle(Throwable $exception): mixed;

    public function mapException(string $exceptionClassFrom, string $exceptionClassTo): static;
}
