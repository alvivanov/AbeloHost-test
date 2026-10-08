<?php

declare(strict_types=1);

use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Category\MysqlCategoryRepository;
use App\Repository\Post\MysqlPostRepository;
use App\Repository\Post\PostRepositoryInterface;
use function DI\autowire;

return [
    CategoryRepositoryInterface::class => autowire(MysqlCategoryRepository::class),
    PostRepositoryInterface::class => autowire(MysqlPostRepository::class),
];
