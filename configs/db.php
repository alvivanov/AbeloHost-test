<?php

declare(strict_types=1);

use function DI\env;

return [
    'db.host' => env('DB_HOST', 'db'),
    'db.name' => env('DB_DATABASE', ''),
    'db.user' => env('DB_USERNAME', ''),
    'db.password' => env('DB_PASSWORD', ''),
];
