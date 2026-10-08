<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repository\Category\CategoryRepositoryInterface;
use Framework\ViewFactory\ViewFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class HomeController
{
    public function __construct(
        private readonly ViewFactoryInterface        $viewFactory,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly string                      $defaultPreviewImage,
    ) {
    }

    public function index(): ResponseInterface
    {
        return $this->viewFactory->create('home.tpl', [
            'categoriesWithPosts' => $this->categoryRepository->allWithPostsOrderedByPublishedAt(3),
            'postDefaultImage' => $this->defaultPreviewImage,
        ]);
    }
}
