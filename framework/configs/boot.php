<?php

declare(strict_types=1);

use function DI\string;

return [
    'boot.basePath' => dirname(__DIR__, 2),
    'boot.logsDir' => string('{boot.basePath}/runtime/logs'),
    'boot.logLevel' => getenv('LOG_LEVEL') ?: 'error',
    'boot.cacheDir' => string('{boot.basePath}/runtime/cache'),
    'boot.viewDir' => string('{boot.basePath}/views'),
    'boot.exceptions' => [
        string('{boot.basePath}/framework/configs/exceptions.php'),
        string('{boot.basePath}/app/configs/exceptions.php')
    ],
    'boot.routes' => [string('{boot.basePath}/app/configs/routes.php')],
];
