<?php

declare(strict_types=1);

namespace Tests\Functional;

use Framework\Application;
use Framework\ContainerBuilder\PhpDiContainerBuilderInterfaceAdapter;
use Nyholm\Psr7\ServerRequest;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

abstract class FunctionalTestCase extends TestCase
{
    private Application $app;
    private PDO $pdo;

    protected function setUp(): void
    {
        $basePath = dirname(__DIR__, 2);

        $container = new PhpDiContainerBuilderInterfaceAdapter()->build([
            "{$basePath}/framework/configs/boot.php",
            "{$basePath}/framework/configs/dependencies.php",
            "{$basePath}/configs/dependencies.php",
            "{$basePath}/configs/db.php",
            "{$basePath}/configs/params.php",
            "{$basePath}/configs/boot_test.php",
        ]);

        $this->app = Application::create($container);
        $this->pdo = $container->get(PDO::class);
    }

    protected function tearDown(): void
    {
        restore_exception_handler();
        $this->cleanDatabase();
    }

    protected function get(string $uri): ResponseInterface
    {
        return $this->app->handleRequest(new ServerRequest('GET', $uri));
    }

    protected static function body(ResponseInterface $response): string
    {
        return (string)$response->getBody();
    }

    /**
     * @param array<string, mixed> $row
     */
    protected function insert(string $table, array $row): int
    {
        $columns = array_keys($row);
        $placeholders = array_map(static fn (string $column): string => ":{$column}", $columns);

        $statement = $this->pdo->prepare(sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table,
            implode('`, `', $columns),
            implode(', ', $placeholders),
        ));
        $statement->execute($row);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    protected function insertMany(string $table, array $rows): void
    {
        foreach ($rows as $row) {
            $this->insert($table, $row);
        }
    }

    private function cleanDatabase(): void
    {
        $tables = $this->pdo
            ->query('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()')
            ->fetchAll(PDO::FETCH_COLUMN);

        if ($tables === []) {
            return;
        }

        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tables as $table) {
            $this->pdo->exec("TRUNCATE TABLE `{$table}`");
        }
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}
