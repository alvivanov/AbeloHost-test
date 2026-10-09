<?php

declare(strict_types=1);

namespace App\Repository\Category;

use App\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return list<Category>
     */
    public function allWithPostsOrderedByPublishedAt(int $postLimit): array;

    public function findOne(int $id): Category;
}
