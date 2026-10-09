<?php

declare(strict_types=1);

namespace Tests\Functional;

final class HomeControllerTest extends FunctionalTestCase
{
    public function testHomePageListsCategoriesWithPosts(): void
    {
        $this->insertMany('categories', [
            ['id' => 1, 'name' => 'Технологии', 'description' => 'Новости и статьи о технологиях.'],
            ['id' => 2, 'name' => 'Путешествия', 'description' => 'Истории и советы путешественникам.'],
            ['id' => 3, 'name' => 'Кулинария', 'description' => 'Рецепты и кулинарные хитрости.'],
        ]);

        $this->insertMany('posts', [
            ['id' => 1, 'image_path' => '/post/1/original.webp', 'preview_image_path' => '/post/1/preview.webp', 'title' => 'REST vs GraphQL', 'description' => 'desc', 'content' => 'content', 'view_count' => 0, 'published_at' => '2026-10-07'],
            ['id' => 2, 'image_path' => '/post/2/original.webp', 'preview_image_path' => '/post/2/preview.webp', 'title' => 'Путешествие по Японии', 'description' => 'desc', 'content' => 'content', 'view_count' => 0, 'published_at' => '2026-10-03'],
            ['id' => 3, 'image_path' => '/post/3/original.webp', 'preview_image_path' => '/post/3/preview.webp', 'title' => 'Рецепт домашней пасты', 'description' => 'desc', 'content' => 'content', 'view_count' => 0, 'published_at' => '2026-10-05'],
        ]);

        $this->insertMany('post_categories', [
            ['post_id' => 1, 'category_id' => 1, 'is_main' => 1],
            ['post_id' => 2, 'category_id' => 2, 'is_main' => 1],
            ['post_id' => 3, 'category_id' => 3, 'is_main' => 1],
        ]);

        $response = $this->get('/');

        self::assertSame(200, $response->getStatusCode());

        $body = self::body($response);
        $this->assertStringContainsString('Технологии', $body);
        $this->assertStringContainsString('Путешествия', $body);
        $this->assertStringContainsString('Кулинария', $body);
        $this->assertStringContainsString('REST vs GraphQL', $body);
    }
}
