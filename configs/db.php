<?php

declare(strict_types=1);

return [
    'db.host' => getenv('DB_HOST') ?: 'db',
    'db.name' => getenv('DB_DATABASE') ?: '',
    'db.user' => getenv('DB_USERNAME') ?: '',
    'db.password' => getenv('DB_PASSWORD') ?: '',
];
