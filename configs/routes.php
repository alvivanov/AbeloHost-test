<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use Framework\Routing\RouterInterface;

return static function (RouterInterface $router): void {
    $router->get('/', [HomeController::class, 'index']);
};
