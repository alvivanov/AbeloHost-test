<?php

declare(strict_types=1);

namespace App\Http\Request;

use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetCategoryPostsRequest
{
    private const int PER_PAGE = 6;
    private const int DEFAULT_PAGE = 1;
    private const string DEFAULT_SORT_BY = 'published_at';
    private const string DEFAULT_SORT_DIRECTION = 'DESC';

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

    public function getSortBy(): ?string
    {
        $value = $this->request->getQueryParams()['sortBy'] ?? null;

        if (!in_array($value, ['view_count', 'published_at'], true)) {
            $value = self::DEFAULT_SORT_BY;
        }

        return $value;
    }

    public function getSortDirection(): ?string
    {
        $value = $this->request->getQueryParams()['sortDirection'] ?? null;

        if (!in_array($value, ['ASC', 'DESC'], true)) {
            $value = self::DEFAULT_SORT_DIRECTION;
        }

        return $value;
    }

    public function getPage(): int
    {
        return max(
            filter_var($this->request->getQueryParams()['page'] ?? null, FILTER_VALIDATE_INT) ?: null,
            self::DEFAULT_PAGE
        );
    }

    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }
}
