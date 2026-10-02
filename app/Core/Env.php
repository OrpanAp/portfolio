<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class Env
{
    /**
     * Load environment variables from a .env file.
     */
    public static function load(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);

        if ($lines === false) {
            throw new RuntimeException(
                'Unable to read environment file.'
            );
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode(
                '=',
                $line,
                2
            );

            $key = trim($key);
            $value = trim($value);

            if ($key === '') {
                continue;
            }

            if (
                strlen($value) >= 2 &&
                (
                    (
                        $value[0] === '"' &&
                        $value[strlen($value) - 1] === '"'
                    ) ||
                    (
                        $value[0] === "'" &&
                        $value[strlen($value) - 1] === "'"
                    )
                )
            ) {
                $value = substr(
                    $value,
                    1,
                    -1
                );
            }

            if (
                getenv($key) === false &&
                !isset($_ENV[$key])
            ) {
                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
            }
        }
    }

    /**
     * Get an environment variable.
     */
    public static function get(
        string $key,
        ?string $default = null
    ): ?string {
        $value = getenv($key);

        if ($value !== false) {
            return $value;
        }

        if (isset($_ENV[$key])) {
            return (string) $_ENV[$key];
        }

        return $default;
    }
}
