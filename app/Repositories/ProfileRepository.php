<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ProfileRepository
{
    public function __construct(
        private PDO $database
    ) {}

    public function get(): ?array
    {
        $statement = $this->database->prepare(
            'SELECT
                id,
                full_name,
                headline,
                bio,
                skills,
                experience,
                education,
                profile_image,
                updated_at
             FROM profile
             WHERE id = 1
             LIMIT 1'
        );

        $statement->execute();

        $profile = $statement->fetch();

        return $profile ?: null;
    }
}
