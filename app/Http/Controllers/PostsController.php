<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Request\GetCategoryPostsRequest;
use App\Http\Request\GetOnePostRequest;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Post\PostRepositoryInterface;
use Framework\ViewFactory\ViewFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class PostsController
{
    public function __construct(
        private ViewFactoryInterface        $viewFactory,
        private PostRepositoryInterface     $postRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private string                      $defaultImage,
        private string                      $defaultPreviewImage,
    ) {
    }

    public function getOne(GetOnePostRequest $request): ResponseInterface
    {
        return $this->viewFactory->create('post.tpl', [
            'post' => $this->postRepository->findByIdAndCategoryId($request->getPostId(), $request->getCategoryId()),
            'category' => $this->categoryRepository->findOne($request->getCategoryId()),
            'postDefaultImage' => $this->defaultImage,
            'postDefaultPreviewImage' => $this->defaultPreviewImage,
            'relatedPosts' => $this->postRepository->findRelatedByPostIdAndCategoryId($request->getPostId(), 3),
        ]);
    }

    public function getAllByCategoryId(GetCategoryPostsRequest $request): ResponseInterface
    {
        return $this->viewFactory->create('category.tpl', [
            'category' => $this->categoryRepository->findOne($request->getCategoryId()),
            'sortBy' => $request->getSortBy(),
            'sortDirection' => $request->getSortDirection(),
            'page' => $request->getPage(),
            'postDefaultImage' => $this->defaultPreviewImage,
            'posts' => $this->postRepository->findAllByCategoryIdPaginated(
                $request->getCategoryId(),
                $request->getPage(),
                $request->getPerPage(),
                $request->getSortBy(),
                $request->getSortDirection()
            ),
            'totalPages' => max(
                1,
                (int)ceil($this->postRepository->getCount($request->getCategoryId()) / $request->getPerPage())
            ),
        ]);
    }
}
