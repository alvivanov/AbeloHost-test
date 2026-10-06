<?php

declare(strict_types=1);

namespace Framework\LoggerFactory;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class MonologLoggerFactory implements LoggerFactoryInterface
{
    public function __construct(private readonly string $logsDir)
    {
    }

    public function create(string $driver, string $level): LoggerInterface
    {
        return new Logger('app')->pushHandler(
            $this->createHandler(LogDriver::from($driver), Level::fromName($level)),
        );
    }

    private function createHandler(LogDriver $driver, Level $level): HandlerInterface
    {
        return match ($driver) {
            LogDriver::ROTATING_FILE => new RotatingFileHandler("$this->logsDir/app.log", level: $level),
            LogDriver::STDOUT => new StreamHandler('php://stdout', $level),
        };
    }
}
