<?php

declare(strict_types=1);

namespace App\Core;

use PDOException;

class Console
{
    /**
     * Run a CLI command.
     *
     * @param array<int, string> $arguments
     */
    public static function run(array $arguments): void
    {
        $command = $arguments[1] ?? 'help';

        switch ($command) {
            case 'help':
                self::help();
                break;

            case 'db:test':
                self::databaseTest();
                break;

            default:
                fwrite(
                    STDERR,
                    "Unknown command: {$command}" . PHP_EOL
                );

                exit(1);
        }
    }

    /**
     * Display available commands.
     */
    private static function help(): void
    {
        echo "Portfolio CLI" . PHP_EOL;
        echo "=============" . PHP_EOL;
        echo PHP_EOL;
        echo "Available commands:" . PHP_EOL;
        echo "  help       Show available commands" . PHP_EOL;
        echo "  db:test    Test the database connection" . PHP_EOL;
    }

    /**
     * Test the database connection.
     */
    private static function databaseTest(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';

        try {
            new Database(
                $config['host'],
                $config['port'],
                $config['database'],
                $config['username'],
                $config['password']
            );

            echo "Database connection: OK" . PHP_EOL;
        } catch (PDOException $e) {
            fwrite(
                STDERR,
                "Database connection: FAILED" . PHP_EOL
            );

            exit(1);
        }
    }
}
