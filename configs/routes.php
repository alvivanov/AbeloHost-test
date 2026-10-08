<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Framework\Routing\RouterInterface;

return static function (RouterInterface $router): void {
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/categories/{categoryId}/posts', [PostsController::class, 'getAllByCategoryId']);
    $router->get('/categories/{categoryId}/posts/{id}', [PostsController::class, 'getOne']);
};
