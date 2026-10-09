<?php

declare(strict_types=1);

use function DI\string;

return [
    'boot.envFile' => string('{boot.basePath}/.env.test'),
];
