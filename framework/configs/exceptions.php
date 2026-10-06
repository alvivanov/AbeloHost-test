<?php

declare(strict_types=1);

use Framework\ExceptionHandling\ExceptionHandlerInterface;
use Framework\ViewFactory\ViewFactoryInterface;
use League\Route\Http\Exception\HttpExceptionInterface;
use Psr\Http\Message\ResponseInterface;

return static function (ExceptionHandlerInterface $exceptionHandler, ViewFactoryInterface $viewFactory): void {
    $exceptionHandler
        ->dontReport(HttpExceptionInterface::class)
        ->renderable(static fn(HttpExceptionInterface $exception): ResponseInterface => $viewFactory
            ->create('error.tpl', ['statusCode' => $exception->getStatusCode(), 'message' => $exception->getMessage()])
            ->withStatus($exception->getStatusCode())
        )
        ->fallback(static fn(): ResponseInterface => $viewFactory
            ->create('error.tpl', ['statusCode' => 500, 'message' => 'Internal Server Error'])
            ->withStatus(500)
        );
};
