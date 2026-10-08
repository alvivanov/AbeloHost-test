<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

final readonly class Post
{
    public function __construct(
        private(set) int               $id,
        private(set) string            $imagePath,
        private(set) string            $previewImagePath,
        private(set) string            $title,
        private(set) string            $description,
        private(set) string            $content,
        private(set) int               $viewCount,
        private(set) DateTimeImmutable $publishedAt,
        private(set) int               $mainCategoryId,
    ) {
    }
}
