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

    public function update(
        array $profile
    ): void {
        $statement = $this->database->prepare(
            'UPDATE profile
             SET
                full_name = :full_name,
                headline = :headline,
                bio = :bio,
                skills = :skills,
                experience = :experience,
                education = :education,
                profile_image = :profile_image
             WHERE id = 1'
        );

        $statement->execute([
            'full_name' =>
            $profile['full_name'] ?? '',
            'headline' =>
            $profile['headline'] ?? null,
            'bio' =>
            $profile['bio'] ?? null,
            'skills' =>
            $profile['skills'] ?? null,
            'experience' =>
            $profile['experience'] ?? null,
            'education' =>
            $profile['education'] ?? null,
            'profile_image' =>
            $profile['profile_image'] ?? null,
        ]);
    }
}
