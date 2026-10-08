<?php

declare(strict_types=1);

namespace App\Repository\Post;

use App\Entity\Post;
use DateTimeImmutable;
use PDO;

final readonly class MysqlPostRepository implements PostRepositoryInterface
{
    private const array SORTABLE_COLUMNS = ['view_count', 'published_at'];
    private const array SORT_DIRECTIONS = ['ASC', 'DESC'];

    public function __construct(private PDO $pdo)
    {
    }

    public function find(int $id): ?Post
    {
        $statement = $this->pdo->prepare(
            'SELECT p.*, pcm.category_id FROM posts p
             INNER JOIN post_categories pcm ON pcm.post_id = p.id AND pcm.is_main = 1
             WHERE p.id = :id',
        );
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : $this->hydrate($row);
    }

    /** @param array<string, mixed> $row */
    private function hydrate(array $row): Post
    {
        return new Post(
            (int)$row['id'],
            $row['image_path'],
            $row['preview_image_path'],
            $row['title'],
            $row['description'],
            $row['content'],
            (int)$row['view_count'],
            new DateTimeImmutable($row['published_at']),
            (int)$row['category_id'],
        );
    }

    public function findAllByCategoryIdPaginated(
        int     $categoryId,
        int     $page,
        int     $limit,
        ?string $sortBy = null,
        ?string $sortDirection = null
    ): array {
        $sortBy = in_array($sortBy, self::SORTABLE_COLUMNS, true) ? $sortBy : 'published_at';
        $sortDirection = in_array($sortDirection, self::SORT_DIRECTIONS, true) ? $sortDirection : 'DESC';
        $offset = max(0, $page - 1) * $limit;

        $statement = $this->pdo->prepare(
            "SELECT p.*, pcm.category_id FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             INNER JOIN post_categories pcm ON pcm.post_id = p.id AND pcm.is_main = 1
             WHERE pc.category_id = :categoryId
             ORDER BY p.$sortBy $sortDirection
             LIMIT :limit OFFSET :offset",
        );
        $statement->bindValue('categoryId', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return array_map($this->hydrate(...), $statement->fetchAll());
    }

    public function findByIdAndCategoryId(int $id, int $categoryId): ?Post
    {
        $statement = $this->pdo->prepare(
            'SELECT p.*, pcm.category_id FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             INNER JOIN post_categories pcm ON pcm.post_id = p.id AND pcm.is_main = 1
             WHERE p.id = :id AND pc.category_id = :categoryId',
        );
        $statement->execute(['id' => $id, 'categoryId' => $categoryId]);
        $row = $statement->fetch();

        return $row === false ? null : $this->hydrate($row);
    }

    public function getCount(int $categoryId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :categoryId',
        );
        $statement->execute(['categoryId' => $categoryId]);

        return (int)$statement->fetchColumn();
    }

    public function findRelatedByPostIdAndCategoryId(int $postId, int $limit): array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.*, pcm.category_id FROM posts p
             INNER JOIN related_posts rp ON rp.related_post_id = p.id
             INNER JOIN post_categories pcm ON pcm.post_id = p.id AND pcm.is_main = 1
             WHERE rp.post_id = :postId
             ORDER BY p.published_at DESC
             LIMIT :limit',
        );
        $statement->bindValue('postId', $postId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return array_map($this->hydrate(...), $statement->fetchAll());
    }
}
