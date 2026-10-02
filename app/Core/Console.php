<?php

declare(strict_types=1);

namespace App\Core;

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
        echo "  help    Show available commands" . PHP_EOL;
    }
}
