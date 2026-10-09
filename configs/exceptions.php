<?php

declare(strict_types=1);

use Framework\ExceptionHandling\ExceptionHandlerInterface;
use Framework\Repository\EntityNotFoundException;
use League\Route\Http\Exception\NotFoundException;

return static function (ExceptionHandlerInterface $exceptions): void {
    $exceptions->mapException(EntityNotFoundException::class, NotFoundException::class);
};
