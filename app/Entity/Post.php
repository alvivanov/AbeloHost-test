<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

final readonly class Post
{
    /** @param list<Category> $categories */
    public function __construct(
        private(set) int               $id,
        private(set) string            $imagePath,
        private(set) string            $title,
        private(set) string            $description,
        private(set) string            $content,
        private(set) array             $categories,
        private(set) int               $viewCount,
        private(set) DateTimeImmutable $publishedAt,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'image' => $this->imagePath,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'views' => $this->viewCount,
            'publishedAt' => $this->publishedAt->format('Y-m-d'),
        ];
    }
}
