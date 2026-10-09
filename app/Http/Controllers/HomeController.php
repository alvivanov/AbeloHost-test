<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repository\Category\CategoryRepositoryInterface;
use Framework\ViewFactory\ViewFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class HomeController
{
    public function __construct(
        private ViewFactoryInterface        $viewFactory,
        private CategoryRepositoryInterface $categoryRepository,
        private string                      $defaultPreviewImage,
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
