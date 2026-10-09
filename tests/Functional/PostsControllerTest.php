<?php

declare(strict_types=1);

namespace Tests\Functional;

final class PostsControllerTest extends FunctionalTestCase
{
    public function testCategoryPageListsItsPosts(): void
    {
        $this->insert('categories', ['id' => 1, 'name' => 'Технологии', 'description' => 'desc']);
        $this->insert('posts', $this->postFixture(1, 'REST vs GraphQL', '2026-10-07'));
        $this->insert('post_categories', ['post_id' => 1, 'category_id' => 1, 'is_main' => 1]);

        $response = $this->get('/categories/1/posts');

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->body($response);
        $this->assertStringContainsString('Технологии', $body);
        $this->assertStringContainsString('REST vs GraphQL', $body);
    }

    public function testCategoryPageReturnsNotFoundForUnknownCategory(): void
    {
        $response = $this->get('/categories/999/posts');

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testCategoryPageAppliesSorting(): void
    {
        $this->insert('categories', ['id' => 1, 'name' => 'Технологии', 'description' => 'desc']);
        $this->insert('posts', $this->postFixture(1, 'REST vs GraphQL', '2026-10-07', viewCount: 10));
        $this->insert('post_categories', ['post_id' => 1, 'category_id' => 1, 'is_main' => 1]);

        $response = $this->get('/categories/1/posts?sortBy=view_count&sortDirection=ASC');

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testCategoryPagePaginatesPosts(): void
    {
        $this->insert('categories', ['id' => 1, 'name' => 'Технологии', 'description' => 'desc']);

        $this->insertMany('posts', [
            $this->postFixture(1, 'REST vs GraphQL', '2026-10-07'),
            $this->postFixture(2, 'Пост 2', '2026-10-06'),
            $this->postFixture(3, 'Пост 3', '2026-10-05'),
            $this->postFixture(4, 'Пост 4', '2026-10-04'),
            $this->postFixture(5, 'Пост 5', '2026-10-03'),
            $this->postFixture(6, 'Пост 6', '2026-10-02'),
            $this->postFixture(7, 'Тестирование на PHPUnit с нуля', '2026-09-01'),
        ]);

        $this->insertMany('post_categories', array_map(
            static fn (int $postId): array => ['post_id' => $postId, 'category_id' => 1, 'is_main' => 1],
            range(1, 7),
        ));

        $firstPage = $this->body($this->get('/categories/1/posts'));
        $secondPage = $this->body($this->get('/categories/1/posts?page=2'));

        $this->assertStringContainsString('REST vs GraphQL', $firstPage);
        $this->assertStringNotContainsString('Тестирование на PHPUnit с нуля', $firstPage);

        $this->assertStringContainsString('Тестирование на PHPUnit с нуля', $secondPage);
        $this->assertStringNotContainsString('REST vs GraphQL', $secondPage);
    }

    public function testPostPageIsShownForItsOwnCategory(): void
    {
        $this->insert('categories', ['id' => 3, 'name' => 'Кулинария', 'description' => 'desc']);
        $this->insert('posts', $this->postFixture(9, 'Гастрономический тур по Италии', '2026-10-02'));
        $this->insert('post_categories', ['post_id' => 9, 'category_id' => 3, 'is_main' => 1]);

        $response = $this->get('/categories/3/posts/9');

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->body($response);
        $this->assertStringContainsString('Гастрономический тур по Италии', $body);
    }

    public function testPostPageIsShownForASecondaryCategory(): void
    {
        $this->insertMany('categories', [
            ['id' => 2, 'name' => 'Путешествия', 'description' => 'desc'],
            ['id' => 3, 'name' => 'Кулинария', 'description' => 'desc'],
        ]);
        $this->insert('posts', $this->postFixture(9, 'Гастрономический тур по Италии', '2026-10-02'));
        $this->insertMany('post_categories', [
            ['post_id' => 9, 'category_id' => 3, 'is_main' => 1],
            ['post_id' => 9, 'category_id' => 2, 'is_main' => 0],
        ]);

        $response = $this->get('/categories/2/posts/9');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Гастрономический тур по Италии', $this->body($response));
    }

    public function testPostPageLinksRelatedPostsToTheirOwnMainCategory(): void
    {
        $this->insertMany('categories', [
            ['id' => 2, 'name' => 'Путешествия', 'description' => 'desc'],
            ['id' => 3, 'name' => 'Кулинария', 'description' => 'desc'],
        ]);

        $this->insertMany('posts', [
            $this->postFixture(9, 'Гастрономический тур по Италии', '2026-10-02'),
            $this->postFixture(6, 'Путешествие по Японии', '2026-10-03'),
            $this->postFixture(7, 'Как собрать рюкзак в поход', '2026-10-02'),
            $this->postFixture(8, 'Бюджетные путешествия по Европе', '2026-10-01'),
        ]);

        $this->insertMany('post_categories', [
            ['post_id' => 9, 'category_id' => 3, 'is_main' => 1],
            ['post_id' => 6, 'category_id' => 2, 'is_main' => 1],
            ['post_id' => 7, 'category_id' => 2, 'is_main' => 1],
            ['post_id' => 8, 'category_id' => 2, 'is_main' => 1],
        ]);

        $this->insertMany('related_posts', [
            ['post_id' => 9, 'related_post_id' => 6],
            ['post_id' => 9, 'related_post_id' => 7],
            ['post_id' => 9, 'related_post_id' => 8],
        ]);

        $body = $this->body($this->get('/categories/3/posts/9'));

        $this->assertStringContainsString('/categories/2/posts/6', $body);
        $this->assertStringContainsString('/categories/2/posts/7', $body);
        $this->assertStringContainsString('/categories/2/posts/8', $body);
    }

    public function testPostPageReturnsNotFoundWhenPostDoesNotBelongToCategory(): void
    {
        $this->insertMany('categories', [
            ['id' => 2, 'name' => 'Путешествия', 'description' => 'desc'],
            ['id' => 3, 'name' => 'Кулинария', 'description' => 'desc'],
        ]);
        $this->insert('posts', $this->postFixture(6, 'Путешествие по Японии', '2026-10-03'));
        $this->insert('post_categories', ['post_id' => 6, 'category_id' => 2, 'is_main' => 1]);

        $response = $this->get('/categories/3/posts/6');

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostPageReturnsNotFoundForUnknownPost(): void
    {
        $response = $this->get('/categories/1/posts/99999');

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostPageReturnsNotFoundForUnknownCategory(): void
    {
        $response = $this->get('/categories/999/posts/1');

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @return array<string, mixed>
     */
    private function postFixture(int $id, string $title, string $publishedAt, int $viewCount = 0): array
    {
        return [
            'id' => $id,
            'image_path' => "/post/{$id}/original.webp",
            'preview_image_path' => "/post/{$id}/preview.webp",
            'title' => $title,
            'description' => 'desc',
            'content' => 'content',
            'view_count' => $viewCount,
            'published_at' => $publishedAt,
        ];
    }
}
