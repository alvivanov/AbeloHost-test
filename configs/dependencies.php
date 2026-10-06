<?php

declare(strict_types=1);

use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Category\MysqlCategoryRepository;
use function DI\autowire;

return [
    CategoryRepositoryInterface::class => autowire(MysqlCategoryRepository::class),
];
