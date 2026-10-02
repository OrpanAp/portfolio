<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class DatabaseInstaller
{
    public function __construct(
        private string $host,
        private string $port,
        private string $database,
        private string $username,
        private string $password
    ) {}

    public function createDatabaseIfMissing(): void
    {
        $dsn = "mysql:host={$this->host};port={$this->port};charset=utf8mb4";

        try {
            $connection = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            $database = str_replace(
                '`',
                '``',
                $this->database
            );

            $connection->exec(
                "CREATE DATABASE IF NOT EXISTS `{$database}`
                 CHARACTER SET utf8mb4
                 COLLATE utf8mb4_unicode_ci"
            );
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Database creation failed: '
                    . $exception->getMessage(),
                0,
                $exception
            );
        }
    }
}
