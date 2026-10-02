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

echo "CLI boot successful.\n";
