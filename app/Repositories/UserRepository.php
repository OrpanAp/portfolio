<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UserRepository
{
    public function __construct(
        private PDO $database
    ) {}

    public function findByEmail(string $email): ?array
    {
        $statement = $this->database->prepare(
            'SELECT
                id,
                username,
                email,
                password,
                role,
                created_at,
                updated_at
             FROM users
             WHERE email = :email
             LIMIT 1'
        );

        $statement->execute([
            'email' => $email,
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->database->prepare(
            'SELECT
                id,
                username,
                email,
                password,
                role,
                created_at,
                updated_at
             FROM users
             WHERE id = :id
             LIMIT 1'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function createAdmin(
        string $username,
        string $email,
        string $passwordHash
    ): int {
        $statement = $this->database->prepare(
            'INSERT INTO users (
                username,
                email,
                password,
                role
             ) VALUES (
                :username,
                :email,
                :password,
                :role
             )'
        );

        $statement->execute([
            'username' => $username,
            'email' => $email,
            'password' => $passwordHash,
            'role' => 'admin',
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function findAdmins(): array
    {
        $statement = $this->database->prepare(
            "SELECT
                id,
                username,
                email,
                role,
                created_at,
                updated_at
             FROM users
             WHERE role = :role
             ORDER BY id ASC"
        );

        $statement->execute([
            'role' => 'admin',
        ]);

        return $statement->fetchAll();
    }

    public function updatePassword(
        int $id,
        string $passwordHash
    ): void {
        $statement = $this->database->prepare(
            'UPDATE users
            SET password = :password
            WHERE id = :id
            AND role = :role'
        );

        $statement->execute([
            'password' => $passwordHash,
            'id' => $id,
            'role' => 'admin',
        ]);
    }

    public function countAdmins(): int
    {
        $statement = $this->database->prepare(
            "SELECT COUNT(*)
             FROM users
             WHERE role = :role"
        );

        $statement->execute([
            'role' => 'admin',
        ]);

        return (int) $statement->fetchColumn();
    }
}
