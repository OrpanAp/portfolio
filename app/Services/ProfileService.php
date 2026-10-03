<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService
{
    public function __construct(
        private ProfileRepository $profileRepository
    ) {}

    public function getProfile(): ?array
    {
        return $this->profileRepository->get();
    }

    public function validate(
        array $profile
    ): ?string {
        $fullName = trim(
            (string) (
                $profile['full_name'] ?? ''
            )
        );

        if ($fullName === '') {
            return 'Full name is required.';
        }

        if (mb_strlen($fullName) > 150) {
            return 'Full name must not exceed 150 characters.';
        }

        $headline = trim(
            (string) (
                $profile['headline'] ?? ''
            )
        );

        if (mb_strlen($headline) > 255) {
            return 'Headline must not exceed 255 characters.';
        }

        return null;
    }

    public function saveProfile(
        array $profile
    ): void {
        $this->profileRepository->update(
            [
                'full_name' =>
                $this->normalize(
                    $profile['full_name'] ?? ''
                ),

                'headline' =>
                $this->normalize(
                    $profile['headline'] ?? null
                ),

                'bio' =>
                $this->normalize(
                    $profile['bio'] ?? null
                ),

                'skills' =>
                $this->normalize(
                    $profile['skills'] ?? null
                ),

                'experience' =>
                $this->normalize(
                    $profile['experience'] ?? null
                ),

                'education' =>
                $this->normalize(
                    $profile['education'] ?? null
                ),

                'profile_image' =>
                $this->normalize(
                    $profile['profile_image'] ?? null
                ),
            ]
        );
    }

    private function normalize(
        mixed $value
    ): ?string {
        $value = trim(
            (string) $value
        );

        return $value === ''
            ? null
            : $value;
    }
}
