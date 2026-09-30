<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class SettingRepository
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function get(
        string $key
    ): ?string {
        $statement = $this->pdo->prepare(
            'SELECT setting_value
             FROM settings
             WHERE setting_key = :setting_key
             LIMIT 1'
        );

        $statement->execute([
            'setting_key' => $key,
        ]);

        $value = $statement->fetchColumn();

        if ($value === false) {
            return null;
        }

        return (string) $value;
    }

    public function set(
        string $key,
        ?string $value
    ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO settings
                (setting_key, setting_value)
             VALUES
                (:setting_key, :setting_value)
             ON DUPLICATE KEY UPDATE
                setting_value = VALUES(setting_value)'
        );

        $statement->execute([
            'setting_key' => $key,
            'setting_value' => $value,
        ]);
    }
}
