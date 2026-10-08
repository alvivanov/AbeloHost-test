<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

final readonly class Post
{
    public const string DEFAULT_IMAGE_PREVIEW = '/post/default_preview.png';
    public const string DEFAULT_IMAGE = '/post/default.png';

    public function __construct(
        private(set) int               $id,
        private(set) string            $imagePath,
        private(set) string            $title,
        private(set) string            $description,
        private(set) string            $content,
        private(set) int               $viewCount,
        private(set) DateTimeImmutable $publishedAt,
    )
    {
    }
}
