<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Request\GetCategoryPostsRequest;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Post\PostRepositoryInterface;
use Framework\ViewFactory\ViewFactoryInterface;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PostsController
{
    public function __construct(
        private readonly ViewFactoryInterface        $viewFactory,
        private readonly PostRepositoryInterface     $postRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly string                      $defaultImage,
        private readonly string                      $defaultPreviewImage,
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function getOne(ServerRequestInterface $request): ResponseInterface
    {
        $categoryId = filter_var($request->getAttribute('categoryId'), FILTER_VALIDATE_INT);
        $postId = filter_var($request->getAttribute('id'), FILTER_VALIDATE_INT);

        if ($categoryId === false || $postId === false) {
            throw new NotFoundException('Post not found');
        }

        if (!$category = $this->categoryRepository->findOne($categoryId)) {
            throw new NotFoundException('Post not found');
        }

        if (!$post = $this->postRepository->findByIdAndCategoryId($postId, $categoryId)) {
            throw new NotFoundException('Post not found');
        }

        return $this->viewFactory->create('post.tpl', [
            'post' => $post,
            'category' => $category,
            'postDefaultImage' => $this->defaultImage,
            'postDefaultPreviewImage' => $this->defaultPreviewImage,
            'relatedPosts' => $this->postRepository->findRelatedByPostIdAndCategoryId($postId, 3),
        ]);
    }

    /**
     * @throws NotFoundException
     */
    public function getAllByCategoryId(GetCategoryPostsRequest $request): ResponseInterface
    {
        $requestId = $request->getCategoryId();

        if (!$requestId || !$category = $this->categoryRepository->findOne($requestId)) {
            throw new NotFoundException('Category not found');
        }

        return $this->viewFactory->create('category.tpl', [
            'category' => $category,
            'sortBy' => $request->getSortBy(),
            'sortDirection' => $request->getSortDirection(),
            'page' => $request->getPage(),
            'postDefaultImage' => $this->defaultPreviewImage,
            'posts' => $this->postRepository->findAllByCategoryIdPaginated(
                $requestId,
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
