<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\ProfileService;
use App\Services\UploadService;

class ProfileController
{
    public function __construct(
        private View $view,
        private Request $request,
        private Response $response,
        private Csrf $csrf,
        private ProfileService $profileService,
        private UploadService $uploadService,
        private string $profileImagePath,
        private int $profileImageMaxFileSize,
        private string $appUrl
    ) {}

    public function index(): string
    {
        return $this->view->render(
            'admin.profile.edit',
            [
                'title' => 'Profile',
                'appUrl' => $this->appUrl,
                'csrfField' =>
                $this->csrf->field(),
                'profile' =>
                $this->profileService
                    ->getProfile(),
                'error' => null,
            ],
            'layouts.admin'
        );
    }

    public function update(): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $profile =
            $this->request->allPost();

        unset(
            $profile['_csrf']
        );

        $error =
            $this->profileService
            ->validate($profile);

        if ($error !== null) {
            $this->showError(
                $error,
                $profile
            );

            return;
        }

        $currentProfile =
            $this->profileService
            ->getProfile();

        $oldProfileImage =
            $currentProfile['profile_image']
            ?? null;

        $profile['profile_image'] =
            $oldProfileImage;

        $uploadedProfileImage = null;

        try {
            $profileImageFile =
                $_FILES['profile_image']
                ?? null;

            if (
                is_array($profileImageFile) &&
                (
                    $profileImageFile['error']
                    ?? UPLOAD_ERR_NO_FILE
                ) !== UPLOAD_ERR_NO_FILE
            ) {
                $uploadedProfileImage =
                    $this->uploadService
                    ->uploadProfileImage(
                        $profileImageFile,
                        $this->profileImagePath,
                        $this->profileImageMaxFileSize
                    );

                $profile['profile_image'] =
                    $uploadedProfileImage;
            }

            $this->profileService
                ->saveProfile($profile);
        } catch (\Throwable $e) {
            if ($uploadedProfileImage !== null) {
                $newImageFile =
                    __DIR__
                    . '/../../../public/'
                    . ltrim(
                        $uploadedProfileImage,
                        '/'
                    );

                if (is_file($newImageFile)) {
                    @unlink($newImageFile);
                }
            }

            $this->showError(
                $e->getMessage(),
                $profile
            );

            return;
        }

        if (
            $uploadedProfileImage !== null &&
            is_string($oldProfileImage) &&
            $oldProfileImage !== ''
        ) {
            $oldImageFile =
                __DIR__
                . '/../../../public/'
                . ltrim(
                    $oldProfileImage,
                    '/'
                );

            if (is_file($oldImageFile)) {
                @unlink($oldImageFile);
            }
        }

        $this->response->redirect(
            $this->appUrl . '/admin/profile'
        );
    }

    private function verifyCsrf(): bool
    {
        $token =
            $this->request->post('_csrf');

        if (!$this->csrf->verify($token)) {
            $this->response->send(
                'Invalid CSRF token.',
                403
            );

            return false;
        }

        return true;
    }

    private function showError(
        string $error,
        array $profile
    ): void {
        $currentProfile =
            $this->profileService
            ->getProfile();

        $normalizedProfile =
            $currentProfile ?? [];

        foreach (
            [
                'full_name',
                'headline',
                'bio',
                'skills',
                'experience',
                'education',
            ] as $key
        ) {
            if (
                array_key_exists(
                    $key,
                    $profile
                )
            ) {
                $normalizedProfile[$key] =
                    trim(
                        (string) $profile[$key]
                    );
            }
        }

        $html = $this->view->render(
            'admin.profile.edit',
            [
                'title' => 'Profile',
                'appUrl' => $this->appUrl,
                'csrfField' =>
                $this->csrf->field(),
                'profile' =>
                $normalizedProfile,
                'error' => $error,
            ],
            'layouts.admin'
        );

        $this->response->send(
            $html,
            422
        );
    }
}
