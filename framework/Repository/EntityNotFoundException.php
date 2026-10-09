<?php

declare(strict_types=1);

namespace Framework\Repository;

use RuntimeException;

final class EntityNotFoundException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
