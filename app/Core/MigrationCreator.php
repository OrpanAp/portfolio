<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class MigrationCreator
{
    public function __construct(
        private string $migrationDirectory
    ) {}

    public function normalizeName(string $name): string
    {
        $name = strtolower(trim($name));
        $name = preg_replace('/\\s+/', '_', $name) ?? '';

        if ($name === '' || !preg_match('/^[a-z0-9_]+$/', $name)) {
            throw new RuntimeException(
                'Migration name may contain only letters, numbers, and underscores.'
            );
        }

        return $name;
    }

    public function nextNumber(): int
    {
        $files = glob(
            rtrim($this->migrationDirectory, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . '[0-9][0-9][0-9][0-9]_*.php'
        );

        if ($files === false) {
            return 1;
        }

        $highest = 0;

        foreach ($files as $file) {
            $filename = basename($file);

            if (
                preg_match('/^(\\d{4})_.*\\.php$/', $filename, $matches)
            ) {
                $number = (int) $matches[1];

                if ($number > $highest) {
                    $highest = $number;
                }
            }
        }

        $next = $highest + 1;

        if ($next > 9999) {
            throw new RuntimeException(
                'Migration sequence has reached its maximum of 9999.'
            );
        }

        return $next;
    }

    public function create(string $name): string
    {
        $name = $this->normalizeName($name);

        $files = glob(
            rtrim($this->migrationDirectory, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . '[0-9][0-9][0-9][0-9]_*.php'
        );

        if ($files !== false) {
            foreach ($files as $file) {
                $filename = basename($file);

                if (
                    preg_match(
                        '/^\d{4}_(.+)\.php$/',
                        $filename,
                        $matches
                    )
                    && $matches[1] === $name
                ) {
                    throw new RuntimeException(
                        "Migration already exists: {$filename}"
                    );
                }
            }
        }

        $number = $this->nextNumber();

        $filename = sprintf(
            '%04d_%s.php',
            $number,
            $name
        );

        $path = rtrim(
            $this->migrationDirectory,
            DIRECTORY_SEPARATOR
        ) . DIRECTORY_SEPARATOR . $filename;

        if (is_file($path)) {
            throw new RuntimeException(
                "Migration file already exists: {$filename}"
            );
        }

        if (!is_dir($this->migrationDirectory)) {
            if (!mkdir($this->migrationDirectory, 0777, true)) {
                throw new RuntimeException(
                    'Unable to create the migration directory.'
                );
            }
        }

        $template = <<<'PHP'
<?php

declare(strict_types=1);

return new class {
    public function up(PDO $database): void
    {
        //
    }

    public function down(PDO $database): void
    {
        //
    }
};
PHP;

        if (file_put_contents($path, $template . PHP_EOL) === false) {
            throw new RuntimeException(
                "Unable to create migration file: {$filename}"
            );
        }

        return $path;
    }
}
