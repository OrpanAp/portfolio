<?php

declare(strict_types=1);

namespace App\Core;

use App\Repositories\MigrationRepository;
use PDO;
use RuntimeException;

class MigrationRunner
{
    public function __construct(
        private PDO $database,
        private MigrationRepository $repository,
        private string $migrationDirectory
    ) {}

    public function migrate(): int
    {
        $this->repository->ensureTable();

        $pendingMigrations = [];

        foreach (
            $this->repository->migrationFiles(
                $this->migrationDirectory
            ) as $file
        ) {
            $migrationName = pathinfo($file, PATHINFO_FILENAME);

            if (!$this->repository->exists($migrationName)) {
                $pendingMigrations[] = [
                    'name' => $migrationName,
                    'file' => $file,
                ];
            }
        }

        if ($pendingMigrations === []) {
            return 0;
        }

        $batch = $this->repository->lastBatch() + 1;
        $executed = 0;

        foreach ($pendingMigrations as $migration) {
            $instance = require $migration['file'];

            if (
                !is_object($instance)
                || !method_exists($instance, 'up')
                || !method_exists($instance, 'down')
            ) {
                throw new RuntimeException(
                    "Invalid migration: {$migration['name']}"
                );
            }

            $instance->up($this->database);

            $this->repository->record(
                $migration['name'],
                $batch
            );

            $executed++;
        }

        return $executed;
    }

    public function rollback(): int
    {
        $this->repository->ensureTable();

        $migrations = $this->repository->lastBatchMigrations();

        if ($migrations === []) {
            return 0;
        }

        $rolledBack = 0;

        foreach ($migrations as $migration) {
            if ($migration['migration'] === '0001_baseline') {
                throw new RuntimeException(
                    'Cannot roll back the baseline migration.'
                );
            }

            $file = $this->migrationDirectory
                . DIRECTORY_SEPARATOR
                . $migration['migration']
                . '.php';

            if (!is_file($file)) {
                throw new RuntimeException(
                    "Migration file not found: {$migration['migration']}"
                );
            }

            $instance = require $file;

            if (
                !is_object($instance)
                || !method_exists($instance, 'up')
                || !method_exists($instance, 'down')
            ) {
                throw new RuntimeException(
                    "Invalid migration: {$migration['migration']}"
                );
            }

            $instance->down($this->database);

            $this->repository->remove(
                $migration['migration']
            );

            $rolledBack++;
        }

        return $rolledBack;
    }
}
