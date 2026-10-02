<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$dbConfig = require __DIR__ . '/config/database.php';

$database = new App\Core\Database(
    $dbConfig['host'],
    $dbConfig['port'],
    $dbConfig['database'],
    $dbConfig['username'],
    $dbConfig['password']
);

$command = $argv[1] ?? null;

if ($command === null) {
    echo "Portfolio CLI\n";
    echo "Usage: php cli.php <command>\n";
    exit(0);
}

switch ($command) {
    case 'help':
        echo "Portfolio CLI\n";
        echo "\n";
        echo "Available commands:\n";
        echo "  help    Show this help message\n";
        exit(0);

    default:
        fwrite(
            STDERR,
            "Unknown command: {$command}\n"
        );
        exit(1);
}
