<?php

declare(strict_types=1);

use function DI\env;
use function DI\string;

return [
    'boot.basePath' => dirname(__DIR__, 2),
    'boot.appBaseUrl' => env('APP_URL', 'http://127.0.0.1/'),
    'boot.logsDir' => string('{boot.basePath}/runtime/logs'),
    'boot.envFile' => string('{boot.basePath}/.env'),
    'boot.logLevel' => env('LOG_LEVEL', 'error'),
    'boot.logDriver' => env('LOG_DRIVER', 'stdout'),
    'boot.cacheDir' => string('{boot.basePath}/runtime/cache'),
    'boot.viewDir' => string('{boot.basePath}/views'),
    'boot.storageDir' => string('{boot.basePath}/public/storage'),
    'boot.exceptions' => [
        string('{boot.basePath}/framework/configs/exceptions.php'),
        string('{boot.basePath}/configs/exceptions.php'),
    ],
    'boot.routes' => [string('{boot.basePath}/configs/routes.php')],
];
