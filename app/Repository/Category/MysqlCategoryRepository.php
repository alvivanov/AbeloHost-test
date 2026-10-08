<?php

declare(strict_types=1);

namespace App\Repository\Category;

use App\Entity\Category;
use App\Entity\Post;
use DateTimeImmutable;
use PDO;

final readonly class MysqlCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function allWithPostsOrderedByPublishedAt(int $postLimit): array
    {
        $statement = $this->pdo->prepare(
            'WITH ranked_posts AS (
                SELECT
                    p.id, p.image_path, p.preview_image_path, p.title, p.description, p.content, p.view_count, p.published_at,
                    pc.category_id,
                    ROW_NUMBER() OVER (PARTITION BY pc.category_id ORDER BY p.published_at DESC) AS rn
                FROM posts p
                INNER JOIN post_categories pc ON pc.post_id = p.id
            )
            SELECT
                c.id AS category_id, c.name AS category_name, c.description AS category_description,
                rp.id AS post_id, rp.image_path, rp.preview_image_path, rp.title AS post_title, rp.description AS post_description,
                rp.content AS post_content, rp.view_count, rp.published_at
            FROM categories c
            INNER JOIN ranked_posts rp ON rp.category_id = c.id AND rp.rn <= :postLimit
            ORDER BY c.id, rp.published_at DESC',
        );
        $statement->bindValue('postLimit', $postLimit, PDO::PARAM_INT);
        $statement->execute();

        $categoriesData = [];

        foreach ($statement->fetchAll() as $row) {
            $categoryId = (int)$row['category_id'];

            if (!isset($categoriesData[$categoryId])) {
                $categoriesData[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $row['category_name'],
                    'description' => $row['category_description'],
                    'posts' => [],
                ];
            }

            $categoriesData[$categoryId]['posts'][] = new Post(
                (int)$row['post_id'],
                $row['image_path'],
                $row['preview_image_path'],
                $row['post_title'],
                $row['post_description'],
                $row['post_content'],
                (int)$row['view_count'],
                new DateTimeImmutable($row['published_at']),
            );
        }

        return array_map(fn(array $categoryData): Category => $this->hydrate($categoryData), array_values($categoriesData));
    }

    public function findOne(int $id): ?Category
    {
        $statement = $this->pdo->prepare('SELECT id, name, description FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : new Category((int)$row['id'], $row['name'], $row['description']);
    }

    private function hydrate(array $row): Category
    {
        return new Category((int)$row['id'], $row['name'], $row['description'], $row['posts']);
    }
}
