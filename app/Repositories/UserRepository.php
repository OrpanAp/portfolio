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
}
