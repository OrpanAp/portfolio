<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class CategoryRepository
{
    public function __construct(
        private PDO $database
    ) {}

    public function getAll(): array
    {
        $statement = $this->database->prepare(
            'SELECT
                id,
                name,
                slug,
                created_at,
                updated_at
             FROM categories
             ORDER BY name ASC'
        );

        $statement->execute();

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->database->prepare(
            'SELECT
                id,
                name,
                slug,
                created_at,
                updated_at
             FROM categories
             WHERE id = :id
             LIMIT 1'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $category = $statement->fetch();

        return $category ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->database->prepare(
            'SELECT
                id,
                name,
                slug,
                created_at,
                updated_at
             FROM categories
             WHERE slug = :slug
             LIMIT 1'
        );

        $statement->execute([
            'slug' => $slug,
        ]);

        $category = $statement->fetch();

        return $category ?: null;
    }

    public function create(
        string $name,
        string $slug
    ): int {
        $statement = $this->database->prepare(
            'INSERT INTO categories
                (name, slug)
             VALUES
                (:name, :slug)'
        );

        $statement->execute([
            'name' => $name,
            'slug' => $slug,
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function update(
        int $id,
        string $name,
        string $slug
    ): bool {
        $statement = $this->database->prepare(
            'UPDATE categories
             SET
                name = :name,
                slug = :slug
             WHERE id = :id'
        );

        return $statement->execute([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->database->prepare(
            'DELETE FROM categories
             WHERE id = :id'
        );

        return $statement->execute([
            'id' => $id,
        ]);
    }
}
