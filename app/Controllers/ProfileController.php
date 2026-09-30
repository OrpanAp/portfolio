<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\ProfileRepository;

class ProfileController
{
    public function __construct(
        private View $view,
        private ProfileRepository $profileRepository,
        private string $appUrl = ''
    ) {}

    public function index(): string
    {
        $profile = $this->profileRepository->get();

        return $this->view->render(
            'profile.index',
            [
                'title' => 'Profile - My Portfolio',
                'appUrl' => $this->appUrl,
                'profile' => $profile,
            ]
        );
    }
}
