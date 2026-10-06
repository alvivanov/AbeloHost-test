<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\Category\CategoryRepositoryInterface;
use Framework\ViewFactory\ViewFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class HomeController
{
    public function __construct(
        private readonly ViewFactoryInterface        $viewFactory,
        private readonly CategoryRepositoryInterface $categoryRepository,
    )
    {
    }

    public function index(): ResponseInterface
    {
        return $this->viewFactory->create('home.tpl', [
            'categoriesWithPosts' => $this->categoryRepository->allWithPostsOrderedByPublishedAt(3),
            'postDefaultImage' => Post::DEFAULT_IMAGE_PREVIEW,
        ]);
    }
}
