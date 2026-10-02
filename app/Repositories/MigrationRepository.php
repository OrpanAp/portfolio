<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class MigrationRepository
{
    public function __construct(
        private PDO $database
    ) {}

    public function ensureTable(): void
    {
        $this->database->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                migration VARCHAR(255) NOT NULL,
                batch INT UNSIGNED NOT NULL,
                executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_migrations_migration (migration),
                KEY idx_migrations_batch (batch)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci'
        );
    }

    public function migrationFiles(string $directory): array
    {
        $files = glob(
            rtrim($directory, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . '*.php'
        );

        if ($files === false) {
            return [];
        }

        sort($files, SORT_STRING);

        return $files;
    }

    public function all(): array
    {
        $statement = $this->database->query(
            'SELECT id, migration, batch, executed_at
             FROM migrations
             ORDER BY id ASC'
        );

        return $statement->fetchAll();
    }

    public function exists(string $migration): bool
    {
        $statement = $this->database->prepare(
            'SELECT COUNT(*)
             FROM migrations
             WHERE migration = :migration'
        );

        $statement->execute([
            'migration' => $migration,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function lastBatch(): int
    {
        $statement = $this->database->query(
            'SELECT COALESCE(MAX(batch), 0)
             FROM migrations'
        );

        return (int) $statement->fetchColumn();
    }

    public function record(
        string $migration,
        int $batch
    ): void {
        $statement = $this->database->prepare(
            'INSERT INTO migrations (
                migration,
                batch
             ) VALUES (
                :migration,
                :batch
             )'
        );

        $statement->execute([
            'migration' => $migration,
            'batch' => $batch,
        ]);
    }

    public function remove(string $migration): void
    {
        $statement = $this->database->prepare(
            'DELETE FROM migrations
             WHERE migration = :migration'
        );

        $statement->execute([
            'migration' => $migration,
        ]);
    }

    public function lastBatchMigrations(): array
    {
        $batch = $this->lastBatch();

        if ($batch === 0) {
            return [];
        }

        $statement = $this->database->prepare(
            'SELECT id, migration, batch, executed_at
         FROM migrations
         WHERE batch = :batch
         ORDER BY id DESC'
        );

        $statement->execute([
            'batch' => $batch,
        ]);

        return $statement->fetchAll();
    }
}
