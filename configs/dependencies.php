<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Category\MysqlCategoryRepository;
use App\Repository\Post\MysqlPostRepository;
use App\Repository\Post\PostRepositoryInterface;
use function DI\autowire;
use function DI\get;

return [
    CategoryRepositoryInterface::class => autowire(MysqlCategoryRepository::class),
    PostRepositoryInterface::class => autowire(MysqlPostRepository::class),
    HomeController::class => autowire(HomeController::class)
        ->constructorParameter('defaultPreviewImage', get('post.default_preview_image')),
    PostsController::class => autowire(PostsController::class)
        ->constructorParameter('defaultImage', get('post.default_image'))
        ->constructorParameter('defaultPreviewImage', get('post.default_preview_image')),
];
