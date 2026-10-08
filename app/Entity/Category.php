<?php

declare(strict_types=1);

namespace App\Entity;

final readonly class Category
{
    public function __construct(
        private(set) int    $id,
        private(set) string $name,
        private(set) string $description,
        private(set) array  $posts = [],
    ) {
    }
}
