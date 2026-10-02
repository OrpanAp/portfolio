<?php

declare(strict_types=1);

return new class {
    public function up(PDO $database): void
    {
        // Baseline migration.
        //
        // The current database schema already exists through
        // database/schema.sql. This migration intentionally makes
        // no changes to the existing database.
    }

    public function down(PDO $database): void
    {
        // Baseline migration cannot be rolled back because it
        // represents the existing database state.
    }
};
