<?php

declare(strict_types=1);

namespace App\Http\Request;

use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetOnePostRequest
{
    public function __construct(private ServerRequestInterface $request)
    {
    }

    /**
     * @throws NotFoundException
     */
    public function getCategoryId(): int
    {
        return filter_var($this->request->getAttribute('categoryId'), FILTER_VALIDATE_INT)
            ?: throw new NotFoundException('Category not found');
    }

    /**
     * @throws NotFoundException
     */
    public function getPostId(): int
    {
        return filter_var($this->request->getAttribute('id'), FILTER_VALIDATE_INT)
            ?: throw new NotFoundException('Post not found');
    }
}
