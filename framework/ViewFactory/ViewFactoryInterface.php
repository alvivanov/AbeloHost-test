<?php

namespace Framework\ViewFactory;
use Psr\Http\Message\ResponseInterface;

interface ViewFactoryInterface
{
    public function create(string $view, array $params): ResponseInterface;
}