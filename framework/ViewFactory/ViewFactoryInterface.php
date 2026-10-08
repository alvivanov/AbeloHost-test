<?php

declare(strict_types=1);

namespace Framework\ViewFactory;

use Psr\Http\Message\ResponseInterface;

interface ViewFactoryInterface
{
    public function create(string $view, array $params): ResponseInterface;
}
