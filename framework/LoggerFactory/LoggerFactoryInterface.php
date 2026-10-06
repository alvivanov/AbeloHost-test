<?php

declare(strict_types=1);

namespace Framework\LoggerFactory;

use Psr\Log\LoggerInterface;

interface LoggerFactoryInterface
{
    public function create(string $driver, string $level): LoggerInterface;
}
