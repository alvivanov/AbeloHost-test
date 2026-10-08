<?php

declare(strict_types=1);

namespace App\Repository\Post;

use App\Entity\Post;

interface PostRepositoryInterface
{
    public function findByIdAndCategoryId(int $id, int $categoryId): ?Post;

    /**
     * @return Post[]
     */
    public function findAllByCategoryIdPaginated(
        int     $categoryId,
        int     $page,
        int     $limit,
        ?string $sortBy = null,
        ?string $sortDirection = null
    ): array;

    public function getCount(int $categoryId): int;
}
